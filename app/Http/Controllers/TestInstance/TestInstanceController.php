<?php

namespace App\Http\Controllers\TestInstance;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\TestInstance\TestInstanceStartExecutionRequest;
use App\Jobs\FinishTestExecutionJob;
use App\Services\TestExecutionService;
use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;

class TestInstanceController extends AuthController
{
    use DispatchesJobs;

    /**
     * @method POST
     * @uri /testinstance/{id}/startExecution
     * @param TestInstanceStartExecutionRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function startExecution(TestInstanceStartExecutionRequest $request, $id)
    {
        $testExecution = TestExecutionService::createTestExecution($request->currentUser->id, $id);
        $maxDuration = TestService::getTestMaxDurationByTestInstanceId($testExecution->test_instance_id);
        $endTime = Carbon::now()->addMinutes($maxDuration);

        // dispatch job on test execution start in case the user doesn't submit the form on time
        $job = (new FinishTestExecutionJob($testExecution->id, $endTime))->delay($maxDuration * 60);
        $this->dipatch($job);

        return redirect('/testexecution/' . $testExecution->id . '/execute');
    }
}
