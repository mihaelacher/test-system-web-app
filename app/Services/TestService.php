<?php

namespace App\Services;

use App\Models\Test\Test;
use App\Models\Test\TestQuestions;
use Illuminate\Support\Facades\DB;

class TestService
{
    /**
     * @param string $name
     * @param string $introText
     * @param int $maxDuration
     * @param int $isVisibleForAdmins
     * @param int $currentUserId
     * @return int
     */
    public static function storeTest(string $name, string $introText, int $maxDuration,
                                     int $isVisibleForAdmins, int $currentUserId): int
    {
        DB::beginTransaction();

        Test::insert([
            'name' => $name,
            'intro_text' => $introText,
            'max_duration' => $maxDuration,
            'is_visible_for_admins' => $isVisibleForAdmins,
            'created_by' => $currentUserId
        ]);

        $lastInsertedRow = DB::select('SELECT LAST_INSERT_ID() as first_transaction_id', [], false);

        DB::commit();

        return $lastInsertedRow[0]->first_transaction_id;
    }

    /**
     * @param int $testId
     * @param array $questionIds
     * @return void
     */
    public static function mapQuestionToTest(int $testId, array $questionIds): void
    {
        TestQuestions::where('test_id', '=', $testId)->delete();

        $rowsForInsert = [];

        foreach ($questionIds as $questionId) {
            $rowsForInsert[] = [
                'test_id' => $testId,
                'question_id' => $questionId
            ];
        }

        TestQuestions::insert($rowsForInsert);
    }
}
