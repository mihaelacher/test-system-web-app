<?php

namespace App\Services;

use App\Models\Authorization\User;
use App\Models\Test\Test;
use App\Models\Test\TestHasVisibleUsers;
use App\Models\Test\TestQuestions;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    /**
     * @param Test $test
     * @param Request $request
     * @return void
     */
    public static function updateTest(Test $test, Request $request)
    {
        $test->name = $request->name;
        $test->intro_text = $request->intro_text;
        $test->max_duration = $request->max_duration;
        $test->is_visible_for_admins = $request->is_visible_for_admins;
        $test->save();
    }

    /**
     * @param User $currentUser
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getTestIndexQueryBasedOnLoggedUser(User $currentUser): \Illuminate\Database\Eloquent\Builder
    {
        $query = Test::query();

        if ($currentUser->is_admin) {
            $query->where('created_by', '=', $currentUser->id)
                ->orWhere('is_visible_for_admins', '=', 1);
        } else {
            $query->join('test_has_visible_users as thvu',
                'thvu.test_id', '=', 'tests.id')
                ->where('thvu.user_id', '=', $currentUser->id);
        }

        return $query;
    }

    /**
     * @param int $testId
     * @param array $userIds
     * @param Carbon $activeFrom
     * @param Carbon $activeTo
     * @return void
     */
    public static function mapUserToTest(int $testId, array $userIds, Carbon $activeFrom, Carbon $activeTo)
    {
        $rowsForInsert = [];

        foreach ($userIds as $userId) {
            $rowsForInsert[] = [
                'active_from' => $activeFrom,
                'active_to' => $activeTo,
                'test_id' => $testId,
                'user_id' => $userId
            ];
        }
        TestHasVisibleUsers::insert($rowsForInsert);
    }
}
