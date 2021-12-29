<?php

namespace App\Policies;

use App\Models\Authorization\User;
use App\Models\Test\TestExecution;
use App\Models\Test\TestInstance;
use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestExecutionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Authorization\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // every logged-in user can see index page
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\Authorization\User $user
     * @param TestExecution $testExecution
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TestExecution $testExecution)
    {
        $testInstance = TestInstance::findOrFail($testExecution->test_instance_id);

        // test execution can be seen by
        // 1. admins who created test invitation
        // 2. owners of the test execution
        return ($user->isAdmin() && $testInstance->created_by === $user->id)
            || TestExecution::findOrFail($testExecution->id)->user_id === $user->id;
    }

    /**
     * Determine whether the user can execute test instance.
     *
     * @param \App\Models\Authorization\User $user
     * @param TestExecution $testExecution
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function execute(User $user, TestExecution $testExecution)
    {
        return TestExecution::where('id', '=', $testExecution->id)
            ->where('user_id', '=', $user->id)
            ->whereNull('end_time')
            ->exists();
    }

    /**
     * Determine whether the user can submit test answer.
     *
     * @param \App\Models\Authorization\User $user
     * @param TestExecution $testExecution
     * @return \Illuminate\Auth\Access\Response|bool
     * @throws \Exception
     */
    public function submitAnswer(User $user, TestExecution $testExecution)
    {
        // answer can be submitted only if execution is still ongoing
        return $user->id === $testExecution->user_id
            && $this->isTestExecutionOpen($testExecution);
    }

    /**
     * Determine whether the user can submit test execution.
     *
     * @param \App\Models\Authorization\User $user
     * @param TestExecution $testExecution
     * @return \Illuminate\Auth\Access\Response|bool
     * @throws \Exception
     */
    public function submit(User $user, TestExecution $testExecution)
    {
        // test execution can be submitted later -> there may be 1-2 minutes delay
        // nevertheless an answer can not be submitted with delay
        return $user->id === $testExecution->user_id;
    }

    /**
     * Determine whether the user can evaluate a test execution.
     *
     * @param \App\Models\Authorization\User $user
     * @param TestExecution $testExecution
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function evaluate(User $user, TestExecution $testExecution)
    {
        $testInstance = TestInstance::findOrFail($testExecution->test_instance_id);

        // user can evaluate test execution only when
        // 1. is admin
        // 2. is creator of the test instance
        // 3. if the executed test has open questions for evaluation
        return $user->isAdmin()
            && $testInstance->created_by === $user->id
            && TestService::doesTestHaveOpenQuestions($testInstance->id);
    }

    //----------------------------------- Utility methods --------------------------------------------//

    /**
     * @param TestExecution $testExecution
     * @return bool
     * @throws \Exception
     */
    private function isTestExecutionOpen(TestExecution $testExecution): bool
    {
        date_default_timezone_set('Europe/Sofia');
        $executionMaxDuration = TestService::getTestMaxDurationByTestInstanceId($testExecution->test_instance_id);

        return Carbon::parse($testExecution->start_time)->addMinutes($executionMaxDuration) > Carbon::now();
    }
}
