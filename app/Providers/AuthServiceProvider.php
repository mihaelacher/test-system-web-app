<?php

namespace App\Providers;

use App\Models\Authorization\User;
use App\Models\Question\Question;
use App\Models\Test\Test;
use App\Models\Test\TestExecution;
use App\Policies\QuestionPolicy;
use App\Policies\TestExecutionPolicy;
use App\Policies\TestPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Question::class => QuestionPolicy::class,
        Test::class => TestPolicy::class,
        TestExecution::class => TestExecutionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
