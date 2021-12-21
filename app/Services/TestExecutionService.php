<?php

namespace App\Services;

use App\Models\Authorization\User;
use App\Models\Question\Question;
use App\Models\Question\QuestionAnswer;
use App\Models\Test\TestExecution;
use App\Models\Test\TestExecutionAnswer;
use App\Models\Test\TestHasVisibleUsers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TestExecutionService
{
    /**
     * @param int $testId
     * @param int $currentUserId
     * @return bool
     */
    public static function isTestActiveForCurrentUser(int $testId, int $currentUserId): bool
    {
        date_default_timezone_set('Europe/Sofia');
        $now = Carbon::now();
        return self::isTestVisibleForCurrentUser($currentUserId, $testId, $now)
            && !self::findTestExecutionInDb($currentUserId, $testId);
    }

    /**
     * @param int $currentUserId
     * @param int $testId
     * @param Carbon $now
     * @return mixed
     */
    private static function isTestVisibleForCurrentUser(int $currentUserId, int $testId, Carbon $now)
    {
        return TestHasVisibleUsers::join('test_instances as ti', 'ti.id', '=', 'test_has_visible_users.test_instance_id')
            ->where('ti.test_id', '=', $testId)
            ->where('test_has_visible_users.user_id', '=', $currentUserId)
            ->where('ti.active_from', '<=', $now)
            ->where('ti.active_to', '>=', $now)
            ->exists();
    }

    /**
     * @param int $currentUserId
     * @param int $testId
     * @param bool $onGoing
     * @return mixed
     */
    public static function findTestExecutionInDb(int $currentUserId, int $testId, bool $onGoing = false)
    {
        $query = TestExecution::where('test_id', '=', $testId)
            ->where('user_id', '=', $currentUserId);

        if ($onGoing) {
            $query->whereNull('end_time');
        } else {
            $query->whereNotNull('end_time');
        }

        return $query->first();
    }

    /**
     * @param int $currentUserId
     * @param int $testId
     * @return TestExecution
     */
    public static function startTestExecution(int $currentUserId, int $testId)
    {
        date_default_timezone_set('Europe/Sofia');

        $testExecution = new TestExecution();
        $testExecution->start_time = Carbon::now();
        $testExecution->user_id = $currentUserId;
        $testExecution->test_id = $testId;
        $testExecution->save();

        return $testExecution;
    }

    /**
     * @param int $testId
     * @return \Illuminate\Support\Collection
     */
    public static function getExecutionQuestionAnswers(int $testId): \Illuminate\Support\Collection
    {
        return DB::table('test_questions as tq')
            ->join('questions as q', 'q.id', '=', 'tq.question_id')
            ->leftJoin('question_answers as qa', 'qa.question_id', '=', 'q.id')
            ->where('tq.test_id', '=', $testId)
            ->select([
                'q.id',
                'q.text',
                'q.instruction',
                'q.points',
                'q.is_open',
                DB::raw('GROUP_CONCAT(CONCAT(qa.id, "-", qa.value)) as answers')
            ])
            ->groupBy('q.id')
            ->get();
    }

    public static function updateTestExecution(TestExecution $testExecution)
    {
        date_default_timezone_set('Europe/Sofia');
        $now = Carbon::now();

        $testExecution->end_time = $now;
        self::updateTestExecutionResultPoints($testExecution);
        $testExecution->save();
    }

    private static function updateTestExecutionResultPoints(TestExecution $testExecution)
    {
        $testId = $testExecution->test_id;
        $testExecutionId = $testExecution->id;
        $questionsArr = self::getQuestionsIdAndPointsArrByTestId($testId);
        $questionAnswers = self::getQuestionAnswersIdAndQuestionIdArrByQuestions(array_keys($questionsArr));
        $testExecutionAnswers = self::getTEAsAnswerAndQuestionIdArrByQuestionsAndTE(array_keys($questionsArr), $testExecutionId);
        $totalPoints = 0;

        foreach ($questionsArr as $questionId => $points) {
            $testExecutionAnswersByQuestion = array_keys($testExecutionAnswers, $questionId);
            $questionAnswersByQuestion = array_keys($questionAnswers, $questionId);

            $difference = array_intersect($testExecutionAnswersByQuestion, $questionAnswersByQuestion);
            $totalPoints += ($points * (count($difference) /  count($questionAnswersByQuestion)));
        }
        $testExecution->result_points = $totalPoints;
    }

    private static function getQuestionAnswersIdAndQuestionIdArrByQuestions(array $questionIds)
    {
        return QuestionAnswer::whereIn('question_id', $questionIds)
            ->where('is_correct', '=', 1)
            ->pluck('question_id', 'id')
            ->toArray();
    }

    private static function getTEAsAnswerAndQuestionIdArrByQuestionsAndTE(array $questionIds, int $testExecutionId)
    {
        return  TestExecutionAnswer::where('test_execution_id', '=', $testExecutionId)
            ->whereIn('question_id', $questionIds)
            ->pluck('question_id', 'question_answer_id')
            ->toArray();
    }

    private static function getQuestionsIdAndPointsArrByTestId(int $testId)
    {
        return Question::join('test_questions as tq', 'tq.question_id', '=', 'questions.id')
            ->where('is_open', '=', 0)
            ->where('tq.test_id', '=', $testId)
            ->pluck('points', 'questions.id')
            ->toArray();
    }

    private static function getCorrectAnswersProQuestion(int $questionId)
    {
        return Question::join('question_answers as qa', 'qa.question_id', '=', 'questions.id')
            ->where('questions.id', '=', $questionId)
            ->where('qa.is_correct', '=', 1)
            ->pluck('qa.id')
            ->toArray();
    }

    public static function getTestExecutionsIndexQueryBasedOnCurrentUser(User $currentUser)
    {
        $query = TestExecution::join('tests as t', 't.id', '=', 'test_executions.test_id');

        if (!$currentUser->is_admin) {
            $query->where('test_executions.user_id', '=', $currentUser->id);
        }

        return $query;
    }

    /**
     * @param int $testExecutionId
     * @param bool $onlyOpen
     * @return mixed
     */
    public static function getTestQuestions(int $testExecutionId, bool $onlyOpen = false)
    {
        $query = Question::join('test_questions as tq', 'tq.question_id', '=', 'questions.id')
            ->leftJoin('test_execution_answers as tea', function ($join) use ($testExecutionId) {
                $join->on('tea.question_id', '=', 'tq.question_id')
                    ->where('tea.test_execution_id', '=', $testExecutionId);
            })
            ->groupBy('questions.id')
            ->select([
                'questions.id',
                'questions.text',
                'questions.points',
                'questions.is_open',
                DB::raw('GROUP_CONCAT(tea.question_answer_id) as answer_ids'),
                'tea.response_text_short'
            ]);
        if ($onlyOpen) {
            $query->where('questions.is_open', '=', 1);
        }
        return $query->get();
    }

    /**
     * @param int $testExecutionId
     * @param int $questionId
     * @param int|null $answerId
     * @param string|null $inputText
     * @return void
     */
    public static function insertTestExecutionAnswer(int $testExecutionId, int $questionId,
                                                     ?int $answerId = null, ?string $inputText = null)
    {
        TestExecutionAnswer::insert([
            'test_execution_id' => $testExecutionId,
            'question_id' => $questionId,
            'question_answer_id' => $answerId,
            'response_text_short' => $inputText
        ]);
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
}
