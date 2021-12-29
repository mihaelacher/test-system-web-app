<?php

namespace App\Jobs;

use App\Services\TestExecutionService;
use Carbon\Carbon;

class FinishTestExecutionJob extends BaseJob
{
    /** @var int $testExecutionId */
    private $testExecutionId;
    /** @var Carbon $endTime */
    private $endTime;

    public function __construct(int $testExecutionId, Carbon $endTime)
    {
        $this->testExecutionId = $testExecutionId;
        $this->endTime = $endTime;
    }

    public function handle()
    {
        TestExecutionService::updateTestExecution($this->testExecutionId, $this->endTime);
    }
}
