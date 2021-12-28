<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainGetRequest;
use App\Models\Test\TestHasVisibleUsers;
use App\Services\TestExecutionService;
use Carbon\Carbon;

class TestExecutionStartRequest extends MainGetRequest
{
    public function authorize()
    {
        date_default_timezone_set('Europe/Sofia');
        $now = Carbon::now();

        return TestExecutionService::isTestVisibleForCurrentUser($this->currentUser->id, request()->testId, $now);
    }
}
