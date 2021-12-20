<?php

namespace App\Services;

use App\Models\Question\Question;
use App\Models\Question\QuestionAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionService
{
    /**
     * @param string $text
     * @param string $instruction
     * @param float $points
     * @param int $typeId
     * @param int $maxMarkableAnswers
     * @param int $isOpen
     * @param int $currentUserId
     * @return int
     */
    public static function storeQuestion(string $text, string $instruction, float $points, int $typeId,
                                         int $maxMarkableAnswers, int $isOpen, int $currentUserId): int
    {
        $question = new Question();
        $question->text = $text;
        $question->instruction = $instruction;
        $question->points = $points;
        $question->question_type_id = $typeId;
        $question->max_markable_answers = $maxMarkableAnswers;
        $question->is_open = $isOpen;
        $question->created_by = $currentUserId;
        $question->save();

        return $question->id;
    }

    /**
     * @param array $answerOrderNum
     * @param array $answerValues
     * @param array $answerIsCorrect
     * @param int $currentUserId
     * @param int $questionId
     * @return void
     */
    public static function storeQuestionAnswers(array $answerOrderNum, array $answerValues, array $answerIsCorrect,
                                                int $currentUserId, int $questionId): void
    {
        $rowsForInsert = [];

        for ($i = 0; $i < 4; $i++) {
            $rowsForInsert[] = [
                'order_num' => $answerOrderNum[$i],
                'value' => $answerValues[$i],
                'is_correct' => $answerIsCorrect[$i],
                'question_id' => $questionId,
                'created_by' => $currentUserId
            ];
        }

        QuestionAnswer::insert($rowsForInsert);
    }

    /**
     * @param Question $question
     * @param Request $request
     * @return void
     */
    public static function updateQuestion(Question $question, Request $request)
    {
        $question->text = $request->text;
        $question->instruction = $request->instruction;
        $question->points = $request->points;
        $question->question_type_id = $request->type;
        $question->max_markable_answers = $request->max_markable_answers;
        $question->is_open = $request->is_open;
        $question->save();
    }

    /**
     * @param array $answerIds
     * @param array $answerOrderNum
     * @param array $answerValues
     * @param array $answerIsCorrect
     * @return void
     */
    public static function updateQuestionAnswers(array $answerIds, array $answerOrderNum, array $answerValues, array $answerIsCorrect)
    {
        foreach ($answerIds as $index => $id) {
            $answer = QuestionAnswer::findOrFail($id);
            $answer->order_num = $answerOrderNum[$index];
            $answer->value = $answerValues[$index];
            $answer->is_correct = $answerIsCorrect[$index];
            $answer->save();
        }
    }

    /**
     * @param int $questionId
     * @return mixed
     */
    public static function getQuestionShowData(int $questionId)
    {
        return Question::join('question_types as qt',
            'qt.id', '=', 'questions.question_type_id')
            ->where('questions.id', '=', $questionId)
            ->select([
                'questions.*',
                'qt.name as type',
            ])
            ->first();
    }
}
