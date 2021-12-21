<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainFormRequest;
use App\Models\Test\Test;
use App\Models\Test\TestExecution;
use Carbon\Carbon;

class TestExecutionSubmitAnswerRequest extends TestExecutionAuthorizeRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return parent::authorize() && $this->isTestExecutionOpen();
    }

    /**
     * @return bool
     */
    private function isTestExecutionOpen(): bool
    {
        date_default_timezone_set('Europe/Sofia');
        $testExecution = TestExecution::findOrFail(request()->id);
        $executionMaxDuration = Test::findOrFail($testExecution->test_id)->max_duration;

        return Carbon::parse($testExecution->start_time)->addMinutes($executionMaxDuration) > Carbon::now();
    }
}
