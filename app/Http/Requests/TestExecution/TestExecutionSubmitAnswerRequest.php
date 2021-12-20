<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainFormRequest;
use App\Models\Test\Test;
use App\Models\Test\TestExecution;
use Carbon\Carbon;

class TestExecutionSubmitAnswerRequest extends MainFormRequest
{
    public function authorize()
    {
        // todo: authorize if user is invited to participate
        return $this->isTestExecutionOpen();
    }

    private function isTestExecutionOpen(): bool
    {
        date_default_timezone_set('Europe/Sofia');
        $testExecution = TestExecution::findOrFail(request()->testExecutionId);
        $executionMaxDuration = Test::findOrFail($testExecution->test_id)->max_duration;

        return Carbon::parse($testExecution->start_time)->addMinutes($executionMaxDuration) > Carbon::now();
    }
}
