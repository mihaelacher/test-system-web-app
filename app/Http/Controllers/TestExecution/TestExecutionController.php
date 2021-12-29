<?php

namespace App\Http\Controllers\TestExecution;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\TestExecution\TestExecutionEvaluateRequest;
use App\Http\Requests\TestExecution\TestExecutionIndexRequest;
use App\Http\Requests\TestExecution\TestExecutionShowRequest;
use App\Http\Requests\TestExecution\TestExecutionStartRequest;
use App\Http\Requests\TestExecution\TestExecutionSubmitEvaluationRequest;
use App\Http\Requests\TestExecution\TestExecutionSubmitRequest;
use App\Models\Test\TestExecution;
use App\Services\TestExecutionService;
use App\Services\TestService;
use App\Util\MessageUtil;
use Carbon\Carbon;
use function redirect;
use function view;

class TestExecutionController extends AuthController
{
    /**
     * @method GET
     * @uri /testexecution/index
     * @param TestExecutionIndexRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(TestExecutionIndexRequest $request)
    {
        return view('test-execution.index');
    }

    /**
     * @method GET
     * @uri /testexecution/{id}/show
     * @param TestExecutionShowRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(TestExecutionShowRequest $request, $id)
    {
        $testExecution = TestExecution::findOrFail($id);

        return view('test-execution.show')
            ->with('testExecution', $testExecution)
            ->with('questions', TestExecutionService::getExecutionQuestionAnswers($testExecution->test_instance_id, $testExecution->id));
    }

    /**
     * @uri /testexecution/{id}/execute
     * @method GET
     * @param TestExecutionStartRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function execute(TestExecutionStartRequest $request, $id)
    {
        date_default_timezone_set('Europe/Sofia');
        $testExecution = TestExecution::findOrFail($id);
        $testInstanceId = $testExecution->test_instance_id;

        $testMaxDuration = TestService::getTestMaxDurationByTestInstanceId($testInstanceId) * 60;
        $timeRemainingInSec = $testMaxDuration - Carbon::parse($testExecution->start_time)->diffInSeconds(Carbon::now());

        return view('test-execution.execute')
            ->with('timeRemainingInSec', $timeRemainingInSec)
            ->with('testExecutionId', $testExecution->id)
            ->with('questions', TestExecutionService::getExecutionQuestionAnswers($testInstanceId, $testExecution->id));
    }


    /**
     * @method POST
     * @uri /testexecution/{id}/submit
     * @param TestExecutionSubmitRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submit(TestExecutionSubmitRequest $request, $id)
    {
        TestExecutionService::updateTestExecution(TestExecution::findOrFail($id));

        MessageUtil::success('You\'ve successfully submitted the test!');

        return redirect('/tests/index');
    }

    /**
     * @method GET
     * @uri /testexecution/{id}/evaluate
     * @param TestExecutionEvaluateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function evaluate(TestExecutionEvaluateRequest $request, $id)
    {
        $testExecution = TestExecution::findOrFail($id);

        return view('test-execution.evaluate')
            ->with('testExecutionId', $testExecution->id)
            ->with('questions', TestExecutionService::getExecutionQuestionAnswers($testExecution->test_instance_id,
                $testExecution->id, true));
    }

    /**
     * @method POST
     * @uri /testexecution/{id}/evaluate
     * @param TestExecutionSubmitEvaluationRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submitEvaluation(TestExecutionSubmitEvaluationRequest $request, $id)
    {
        $testExecution = TestExecution::findOrFail($id);
        $testExecution->result_points += array_sum($request->points);
        $testExecution->save();

        MessageUtil::success('You\'ve successfully evaluated the test!');

        return redirect('/testexecution/index');
    }
}
