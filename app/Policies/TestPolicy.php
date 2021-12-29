<?php

namespace App\Policies;

use App\Models\Authorization\User;
use App\Models\Test\Test;
use App\Models\Test\TestExecution;
use App\Services\TestService;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestPolicy
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
        // index page can be seen from every logged-in user
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\Authorization\User $user
     * @param Test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Test $test)
    {
        // a test can see be seen only from admins
        return ($user->isAdmin() && ($test->created_by === $user->id || $test->is_public));
    }

    /**
     * Determine whether the user can see the model's modal info.
     *
     * @param \App\Models\Authorization\User $user
     * @param Test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function seeModal(User $user, Test $test)
    {
        return TestService::findExistingTestInstanceInDB($user, $test->id, false);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Authorization\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Authorization\User $user
     * @param Test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Test $test)
    {
        // a test can be modified only
        // 1. by test creator
        // 2. if there is no existing test execution in db
        return $test->created_by === $user->id
            && !self::belongsTestToTestExecution($test->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Authorization\User $user
     * @param Test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Test $test)
    {
        // a test can be deleted only
        // 1. by test creator
        // 2. if there is no existing test execution in db
        return $test->created_by === $user->id
            && !self::belongsTestToTestExecution($test->id);
    }

    /**
     * Determine whether the user can execute the test.
     *
     * @param \App\Models\Authorization\User $user
     * @param Test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function startExecution(User $user, Test $test)
    {
        return TestService::findExistingTestInstanceInDB($user, $test->id, true);
    }

    //----------------------------------- Utility methods --------------------------------------------//
    /**
     * @param int $testId
     * @return bool
     */
    private function belongsTestToTestExecution(int $testId): bool
    {
        return TestExecution::join('test_instances as ti', 'ti.id', '=', 'test_executions.test_instance_id')
            ->where('ti.test_id', '=', $testId)
            ->exists();
    }
}
