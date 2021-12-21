<?php

namespace App\Services;

use App\Models\Authorization\User;
use App\Models\Test\Test;
use App\Models\Test\TestHasVisibleUsers;
use App\Models\Test\TestInstance;
use App\Models\Test\TestQuestions;
use Carbon\Carbon;

class TestService
{

    /**
     * @param Test $test
     * @param string $name
     * @param string $introText
     * @param int $maxDuration
     * @param int $isVisibleForAdmins
     * @return int
     */
    public static function updateTest(Test $test, string $name, string $introText, int $maxDuration, int $isVisibleForAdmins): int
    {
        $test->name = $name;
        $test->intro_text = $introText;
        $test->max_duration = $maxDuration;
        $test->is_visible_for_admins = $isVisibleForAdmins;
        $test->save();

        return $test->id;
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
            $query->join('test_instances as ti', 'ti.test_id', '=', 'tests.id')
                ->join('test_has_visible_users as thvu',
                'thvu.test_instance_id', '=', 'ti.id')
                ->where('thvu.user_id', '=', $currentUser->id);
        }

        return $query;
    }

    /**
     * @param int $testId
     * @param Carbon $activeFrom
     * @param Carbon $activeTo
     * @return int
     */
    public static function createTestInstance(int $testId, Carbon $activeFrom, Carbon $activeTo): int
    {
        $testInstance = new TestInstance();
        $testInstance->test_id = $testId;
        $testInstance->active_from = $activeFrom;
        $testInstance->active_to = $activeTo;
        $testInstance->save();

        return $testInstance->id;
    }

    /**
     * @param int $testId
     * @param array $userIds
     * @param Carbon $activeFrom
     * @param Carbon $activeTo
     * @return void
     */
    public static function mapUserToTest(int $testInstanceId, array $userIds)
    {
        $rowsForInsert = [];

        foreach ($userIds as $userId) {
            $rowsForInsert[] = [
                'test_instance_id' => $testInstanceId,
                'user_id' => $userId
            ];
        }
        TestHasVisibleUsers::insert($rowsForInsert);
    }

    /**
     * @param int $testId
     * @return mixed
     */
    public static function doesTestHaveOpenQuestions(int $testId)
    {
        return Test::join('test_questions as tq', 'tq.test_id', '=', 'tests.id')
            ->join('questions as q', 'q.id', '=', 'tq.question_id')
            ->where('tests.id', '=', $testId)
            ->where('q.is_open', '=', 1)
            ->exists();
    }
}
