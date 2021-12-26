<?php

namespace App\Services;

use App\Models\Authorization\User;
use App\Models\Question\QuestionType;
use App\Models\Test\Test;
use App\Models\Test\TestExecution;
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
    public static function setTestAttributes(Test $test, string $name, string $introText,
                                      int $maxDuration, int $isVisibleForAdmins): int
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

        return $query->select(['tests.id', 'name', 'intro_text', 'max_duration']);
    }

    /**
     * @param int $testId
     * @param Carbon $activeFrom
     * @param Carbon $activeTo
     * @param array $userIds
     * @return void
     */
    public static function createTestInstance(int $testId, Carbon $activeFrom, Carbon $activeTo, array $userIds): void
    {
        $testInstance = new TestInstance();
        $testInstance->test_id = $testId;
        $testInstance->active_from = $activeFrom;
        $testInstance->active_to = $activeTo;
        $testInstance->save();

        self::mapUserToTest($testInstance->id, $userIds);
    }

    public static function destroyTest(int $testId)
    {
        TestQuestions::where('test_id', '=', $testId)->delete();
        Test::findOrFail($testId)->delete();
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
            ->whereIn('q.question_type_id', QuestionType::OPEN_QUESTIONS)
            ->exists();
    }

    /**
     * @param int $testId
     * @return bool
     */
    public static function hasTestQuestions(int $testId): bool
    {
        return TestQuestions::where('test_id', '=', $testId)->exists();
    }

    /**
     * @param Test $test
     * @param int $currentUserId
     * @return bool
     */
    public static function canTestBeModified(Test $test, int $currentUserId): bool
    {
        return !TestExecution::where('test_id', '=', $test->id)->exists()
            && $test->created_by === $currentUserId;
    }

    /**
     * @param int $testInstanceId
     * @param array $userIds
     * @return void
     */
    private static function mapUserToTest(int $testInstanceId, array $userIds)
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
}
