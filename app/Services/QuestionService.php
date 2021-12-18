<?php

namespace App\Services;

use App\Models\Question\Question;
use App\Models\Question\QuestionAnswer;
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
        DB::beginTransaction();

        Question::insert([
           'text' => $text,
           'instruction' => $instruction,
           'points' => $points,
           'question_type_id' => $typeId,
           'max_markable_answers' => $maxMarkableAnswers,
           'is_open' => $isOpen,
            'created_by' => $currentUserId
        ]);

        $lastInsertedRow = DB::select('SELECT LAST_INSERT_ID() as first_transaction_id', [], false);

        DB::commit();

        return $lastInsertedRow[0]->first_transaction_id;
    }

    /**
     * @param array $questionsOrderNum
     * @param array $questionValues
     * @param array $questionsIsCorrect
     * @param int $currentUserId
     * @param int $questionId
     * @return void
     */
    public static function storeQuestionAnswers(array $questionsOrderNum, array $questionValues, array $questionsIsCorrect,
                                                int $currentUserId, int $questionId): void
    {
        $rowsForInsert = [];

        for ($i = 0; $i < 4; $i++) {
            $rowsForInsert[] = [
                'order_num' => $questionsOrderNum[$i],
                'value' => $questionValues[$i],
                'is_correct' => $questionsIsCorrect[$i],
                'question_id' => $questionId,
                'created_by' => $currentUserId
            ];
        }

        QuestionAnswer::insert($rowsForInsert);
    }
}
