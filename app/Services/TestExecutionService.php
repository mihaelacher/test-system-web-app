<?php

namespace App\Services;

use App\Models\Authorization\User;
use App\Models\Question\Question;
use App\Models\Question\QuestionType;
use App\Models\Test\Test;
use App\Models\Test\TestExecution;
use App\Models\Test\TestExecutionAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TestExecutionService
{
    /**
     * @param TestExecution $testExecution
     * @return void
     */
    public static function updateTestExecution(TestExecution $testExecution)
    {
        date_default_timezone_set('Europe/Sofia');
        $now = Carbon::now();

        $testExecution->end_time = $now;

        self::updateTestExecutionResultPoints($testExecution);

        $testExecution->save();
    }

    /**
     * @param int $testInstanceId
     * @param int $testExecutionId
     * @param bool $onlyOpen
     * @return mixed
     */
    public static function getExecutionQuestionAnswers(int $testInstanceId, int $testExecutionId, bool $onlyOpen = false)
    {
        $query = Question::join('test_questions as tq', 'tq.question_id', '=', 'questions.id')
            ->join('test_instances as ti', 'ti.test_id', '=', 'tq.test_id')
            ->leftJoin('test_execution_answers as tea', function ($join) use ($testExecutionId) {
                $join->on('tea.question_id', '=', 'tq.question_id')
                    ->where('tea.test_execution_id', '=', $testExecutionId);
            })
            ->where('ti.id', '=', $testInstanceId)
            ->select([
                'questions.id',
                'questions.text',
                'questions.instruction',
                'questions.points',
                'questions.question_type_id',
                'questions.max_markable_answers',
                'tea.response_text_short',
                'tea.response_text_long',
                'tea.response_numeric',
                DB::raw('GROUP_CONCAT(tea.question_answer_id) as closed_question_answers')
            ]);

        if ($onlyOpen) {
            $query->whereIn('questions.question_type_id', QuestionType::OPEN_QUESTIONS);
        }

        return $query->groupBy('questions.id')->get();
    }

    /**
     * @param User $currentUser
     * @return mixed
     */
    public static function getTestExecutionsIndexQueryBasedOnCurrentUser(User $currentUser)
    {
        $query = TestExecution::join('test_instances as ti', 'ti.id', '=', 'test_executions.test_instance_id')
            ->join('tests as t', 't.id', '=', 'ti.test_id');

        // case 1: user can see only their own test executions
        if (!$currentUser->isAdmin()) {
            $query->where('test_executions.user_id', '=', $currentUser->id);
        } else {
            // case 2: admins can see only created from them test instance's executions
            $query->where('ti.created_by', '=', $currentUser->id);
        }

        return $query;
    }

    /**
     * @param int $testExecutionId
     * @param int $questionId
     * @return TestExecutionAnswer
     */
    public static function createTestExecutionAnswer(int $testExecutionId, int $questionId): TestExecutionAnswer
    {
        $questionAnswer = new TestExecutionAnswer();
        $questionAnswer->test_execution_id = $testExecutionId;
        $questionAnswer->question_id = $questionId;

        return $questionAnswer;
    }

    /**
     * @param int $currentUserId
     * @param int $testInstanceId
     * @return int
     */
    public static function createTestExecution(int $currentUserId, int $testInstanceId): int
    {
        $testExecution = new TestExecution();
        $testExecution->start_time = Carbon::now();
        $testExecution->user_id = $currentUserId;
        $testExecution->test_instance_id = $testInstanceId;
        $testExecution->save();

        return $testExecution->id;
    }

    /**
     * @param int $testExecutionId
     * @param int $questionId
     * @return TestExecutionAnswer
     */
    public static function getTestExecutionAnswer(int $testExecutionId, int $questionId): TestExecutionAnswer
    {
        // first look in db if answer already exists
        $questionAnswer = self::findExistingTestExecutionAnswerInDb($testExecutionId, $questionId);

        // no answer is found, create new one
        if (is_null($questionAnswer)) {
            $questionAnswer = self::createTestExecutionAnswer($testExecutionId, $questionId);
        }
        return $questionAnswer;
    }

    /**
     * @param int $testExecutionId
     * @param int $questionId
     * @param int|null $answerId
     * @return mixed
     */
    public static function findExistingTestExecutionAnswerInDb(int $testExecutionId, int $questionId, ?int $answerId = null)
    {
        $query = self::getExecutionAnswerQueryByTestExecutionAndQuestionId($testExecutionId, $questionId);

        if ($answerId) {
            $query->where('question_answer_id', '=', $answerId);
        }

        return $query->first();
    }

    /**
     * @param int $testExecutionId
     * @param int $questionId
     * @return bool
     */
    public static function isMaxMarkableAnswersLimitExceeded(int $testExecutionId, int $questionId): bool
    {
        $maxMarkableAnswersCount = Question::findOrFail($questionId)->max_markable_answers;
        $answersInDb = self::getExecutionAnswerQueryByTestExecutionAndQuestionId($testExecutionId, $questionId)->count();

        return $answersInDb >= $maxMarkableAnswersCount;
    }

    /**
     * @param int $testExecutionId
     * @param int $questionId
     * @return mixed
     */
    private static function getExecutionAnswerQueryByTestExecutionAndQuestionId(int $testExecutionId, int $questionId)
    {
        return TestExecutionAnswer::where('test_execution_id', '=', $testExecutionId)
            ->where('question_id', '=', $questionId);
    }

    /**
     * @param TestExecution $testExecution
     * @return void
     */
    private static function updateTestExecutionResultPoints(TestExecution $testExecution)
    {
        /** @var Test $test */
        $test = TestService::findTestByTestInstance($testExecution->test_instance_id);
        $testExecutionId = $testExecution->id;
        $questions = self::getClosedQuestions($test->id);
        $testExecutionAnswers = self::getTestExecutionAnswers($questions->pluck('id')->toArray(), $testExecutionId);
        $totalPoints = 0;

        foreach ($questions as $question) {
            $correctAnswerIds = $question->answers->where('is_correct', '=', 1)->pluck('id')->toArray();
            $givenAnswers =  array_keys($testExecutionAnswers, $question->id);

            $difference = array_intersect($givenAnswers, $correctAnswerIds);
            $totalPoints += ($question->points * (count($difference) / count($correctAnswerIds)));
        }
        $testExecution->result_points = $totalPoints;
    }

    /**
     * @param int $testId
     * @return mixed
     */
    private static function getClosedQuestions(int $testId)
    {
        return Question::join('test_questions as tq', 'tq.question_id', '=', 'questions.id')
            ->where('tq.test_id', '=', $testId)
            ->whereIn('questions.question_type_id', QuestionType::CLOSED_QUESTIONS)
            ->select('questions.*')
            ->get();
    }

    /**
     * @param array $questionIds
     * @param int $testExecutionId
     * @return mixed
     */
    private static function getTestExecutionAnswers(array $questionIds, int $testExecutionId)
    {
        return TestExecutionAnswer::where('test_execution_id', '=', $testExecutionId)
            ->whereIn('question_id', $questionIds)
            ->pluck('question_id', 'question_answer_id')
            ->toArray();
    }
}
