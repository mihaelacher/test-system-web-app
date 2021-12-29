<?php

namespace App\Policies;

use App\Models\Authorization\User;
use App\Models\Test\TestInstance;
use App\Services\TestService;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestInstancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can execute the test.
     *
     * @param \App\Models\Authorization\User $user
     * @param TestInstance $testInstance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function startExecution(User $user, TestInstance $testInstance)
    {
        return TestService::findExistingTestInstanceInDB($user, $testInstance->test_id, true);
    }
}
