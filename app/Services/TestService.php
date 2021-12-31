<?php

namespace App\Services;

use App\Exceptions\TestInvitationException;
use App\Http\Requests\MainFormRequest;
use App\Http\Requests\Test\StoreTestInvitationsRequest;
use App\Mail\TestInvitation;
use App\Models\Authorization\User;
use App\Models\Question\QuestionType;
use App\Models\Test\Test;
use App\Models\Test\TestHasVisibleUsers;
use App\Models\Test\TestInstance;
use App\Models\Test\TestQuestions;
use App\Util\LogUtil;
use App\Util\MessageUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TestService
{
    /**
     * @param Test $test
     * @param MainFormRequest $request
     * @return void
     */
    public static function handleTestOperations(Test $test, MainFormRequest $request)
    {
        try {
            DB::beginTransaction();

            $testId = self::setTestAttributes($test, $request->name, $request->max_duration,
                $request->is_public, $request->intro_text);

            if (!$testId) {
                throw new TestInvitationException('Test couldn\'t be saved in DB!');
            }

            self::mapQuestionToTest($testId, $request->selected_question_ids);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            LogUtil::logError($e->getMessage());

            MessageUtil::error('Oops...something went wrong!');
        }
    }

    public static function handleTestInvitations(int $testId, StoreTestInvitationsRequest $request)
    {
        try {
            $activeFrom = Carbon::parse($request->active_from);
            $activeTo = Carbon::parse($request->active_to);
            $selectedUserIds = $request->selected_user_ids;

            TestService::createTestInstance($testId, $activeFrom, $activeTo, $selectedUserIds);

            TestService::sendEmailToParticipant($request->currentUser->fullName(), $activeFrom, $activeTo, $selectedUserIds);

        } catch (TestInvitationException $e) {
            LogUtil::logError($e->getMessage());

            MessageUtil::error('Oops, something went wrong!');
        }
    }

    public static function destroyTest(int $testId)
    {
        try {
            DB::beginTransaction();

            TestQuestions::where('test_id', '=', $testId)->delete();
            Test::findOrFail($testId)->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            LogUtil::error($e->getMessage());

            MessageUtil::error('Oops...something went wrong');
        }
    }

    /**
     * @param User $currentUser
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getTestIndexQueryBasedOnLoggedUser(User $currentUser): \Illuminate\Database\Eloquent\Builder
    {
        $query = Test::query();

        // admins can see only public test or tests created by themselves
        if ($currentUser->isAdmin()) {
            $query->where('created_by', '=', $currentUser->id)
                ->orWhere('is_public', '=', 1);
        } else {
            // users see only tests if there is existing test invitation for them
            $query->join('test_instances as ti', 'ti.test_id', '=', 'tests.id')
                ->join('test_has_visible_users as thvu', 'thvu.test_instance_id', '=', 'ti.id')
                ->where('thvu.user_id', '=', $currentUser->id);
        }
        return $query->select(['tests.id', 'name', 'intro_text', 'max_duration']);
    }

    /**
     * @param int $testId
     * @return mixed
     */
    public static function doesTestHaveOpenQuestions(int $testInstanceId)
    {
        return Test::join('test_questions as tq', 'tq.test_id', '=', 'tests.id')
            ->join('test_instances as ti', 'ti.test_id', '=', 'tests.id')
            ->join('questions as q', 'q.id', '=', 'tq.question_id')
            ->where('ti.id', '=', $testInstanceId)
            ->whereIn('q.question_type_id', QuestionType::OPEN_QUESTIONS)
            ->exists();
    }

    /**
     * @param User $currentUser
     * @param int $testId
     * @param bool $onlyActive
     * @return mixed
     */
    public static function findExistingTestInstanceInDB(User $currentUser, int $testId, bool $onlyActive = true)
    {
        date_default_timezone_set('Europe/Sofia');
        $now = Carbon::now();
        $userId = $currentUser->id;

        $query = TestInstance::join('test_has_visible_users as thvu', 'test_instances.id', '=', 'thvu.test_instance_id')
            ->where('test_instances.test_id', '=', $testId)
            ->where('thvu.user_id', '=', $userId);

        if ($onlyActive) {
            $query->leftJoin('test_executions as te', function ($join) use ($userId) {
                $join->on('te.test_instance_id', '=', 'test_instances.id')
                    ->where('te.user_id', '=', $userId);
            })
                ->where('test_instances.active_from', '<=', $now)
                ->where('test_instances.active_to', '>=', $now)
                ->whereNull('te.id');
        }
        return $query->select('test_instances.*')
            ->first();
    }

    /**
     * @param int $testInstanceId
     * @return mixed
     * @throws \Exception
     */
    public static function getTestMaxDurationByTestInstanceId(int $testInstanceId)
    {
        $test = self::findTestByTestInstance($testInstanceId);

        if (is_null($test)) {
            throw new \Exception('Error, no test in DB found.');
        }

        return $test->max_duration;
    }

    /**
     * @param int $testInstanceId
     * @return mixed
     */
    public static function findTestByTestInstance(int $testInstanceId)
    {
        return Test::join('test_instances as ti', 'ti.test_id', '=', 'tests.id')
            ->where('ti.id', '=', $testInstanceId)
            ->select('tests.*')
            ->first();
    }

    /**
     * @param string $creator
     * @param Carbon $fromTime
     * @param Carbon $toTime
     * @param array $invitedUserIds
     * @return void
     */
    private static function sendEmailToParticipant(string $creator, Carbon $fromTime, Carbon $toTime, array $invitedUserIds)
    {
        foreach ($invitedUserIds as $userId) {
            Mail::to(User::findOrFail($userId)->send(new TestInvitation($creator, $fromTime, $toTime)));
        }
    }

    /**
     * @param int $testId
     * @param Carbon $activeFrom
     * @param Carbon $activeTo
     * @param array $userIds
     * @return TestInstance
     */
    private static function createTestInstance(int $testId, Carbon $activeFrom, Carbon $activeTo, array $userIds): TestInstance
    {
        $testInstance = new TestInstance();
        $testInstance->test_id = $testId;
        $testInstance->active_from = $activeFrom;
        $testInstance->active_to = $activeTo;
        $testInstance->save();

        self::mapUserToTest($testInstance->id, $userIds);

        return $testInstance;
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

    /**
     * @param Test $test
     * @param string $name
     * @param int $maxDuration
     * @param int $isPublic
     * @param string|null $introText
     * @return int
     */
    private static function setTestAttributes(Test $test, string $name, int $maxDuration,
                                              int  $isPublic, ?string $introText = null): int
    {
        $test->name = $name;
        $test->intro_text = $introText;
        $test->max_duration = $maxDuration;
        $test->is_public = $isPublic;
        $test->save();

        return $test->id;
    }

    /**
     * @param int $testId
     * @param array $questionIds
     * @return void
     */
    private static function mapQuestionToTest(int $testId, array $questionIds): void
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
