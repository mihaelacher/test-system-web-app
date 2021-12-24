<?php

namespace App\Services;

use App\Http\Requests\MainFormRequest;
use App\Models\Question\Question;
use App\Models\Question\QuestionAnswer;
use App\Models\Question\QuestionType;
use App\Models\Test\TestExecution;
use App\Models\Test\TestQuestions;

class QuestionService
{
    /**
     * @param MainFormRequest $request
     * @param int|null $questionId
     * @return void
     */
    public static function handleQuestionOperations(MainFormRequest $request, ?int $questionId = null)
    {
        $question = $questionId ?? new Question();
        $questionTypeId = $request->type;

        self::deleteQuestionAnswers($questionId);

        $questionId = QuestionService::setQuestionAttributes($question, $request->text, $request->instruction,
            $request->points, $questionTypeId, $request->max_markable_answers);

        if (QuestionService::isQuestionClosed($questionTypeId)) {
            QuestionService::storeQuestionAnswers($request->value, $request->is_correct, $questionId);
        }
    }

    /**
     * @param int $questionTypeId
     * @return bool
     */
    public static function isQuestionClosed(int $questionTypeId): bool
    {
        return in_array($questionTypeId, QuestionType::CLOSED_QUESTIONS);
    }

    /**
     * @param int $questionId
     * @return mixed
     */
    public static function belongsQuestionToTestExecution(int $questionId)
    {
        return TestExecution::join('tests as t', 't.id', '=', 'test_executions.test_id')
            ->join('test_questions as tq', 'tq.test_id', '=', 't.id')
            ->where('tq.question_id', '=', $questionId)
            ->exists();
    }

    /**
     * @param int $questionId
     * @return void
     */
    public static function destroyQuestion(int $questionId)
    {
        self::deleteQuestionAnswers($questionId);
        TestQuestions::where('question_id', '=', $questionId)->delete();
        Question::find($questionId)->delete();
    }

    /**
     * @param Question $question
     * @param string $text
     * @param string $instruction
     * @param float $points
     * @param int $typeId
     * @param int $maxMarkableAnswers
     * @return int
     */
    private static function setQuestionAttributes(Question $question, string $text, string $instruction, float $points,
                                         int $typeId, int $maxMarkableAnswers): int
    {
        $question->text = $text;
        $question->instruction = $instruction;
        $question->points = $points;
        $question->question_type_id = $typeId;
        $question->max_markable_answers = $typeId === QuestionType::MULTIPLE_CHOICE ? $maxMarkableAnswers : 1;
        $question->save();

        return $question->id;
    }

    /**
     * @param array $answerValues
     * @param array $answerIsCorrect
     * @param int $questionId
     * @return void
     */
    private static function storeQuestionAnswers(array $answerValues, array $answerIsCorrect, int $questionId): void
    {
        $rowsForInsert = [];

        for ($i = 0; $i < count($answerValues); $i++) {
            $rowsForInsert[] = [
                'order_num' => $i + 1,
                'value' => $answerValues[$i],
                'is_correct' => $answerIsCorrect[$i],
                'question_id' => $questionId
            ];
        }

        QuestionAnswer::insert($rowsForInsert);
    }

    /**
     * @param int|null $questionId
     * @return void
     */
    private static function deleteQuestionAnswers(?int $questionId)
    {
        if ($questionId) {
            QuestionAnswer::where('question_id', '=', $questionId)->delete();
        }
    }
}
