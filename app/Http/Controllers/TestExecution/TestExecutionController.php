<?php

namespace App\Http\Controllers\TestExecution;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\TestExecution\TestExecutionEvaluateRequest;
use App\Http\Requests\TestExecution\TestExecutionIndexRequest;
use App\Http\Requests\TestExecution\TestExecutionShowRequest;
use App\Http\Requests\TestExecution\TestExecutionStartRequest;
use App\Http\Requests\TestExecution\TestExecutionSubmitEvaluationRequest;
use App\Http\Requests\TestExecution\TestExecutionSubmitRequest;
use App\Models\Test\Test;
use App\Models\Test\TestExecution;
use App\Services\TestExecutionService;
use App\Services\TestService;
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
     * @uri /testexecution/show/{id}
     * @param TestExecutionShowRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(TestExecutionShowRequest $request, $id)
    {
        $testExecution = TestExecution::findOrFail($id);
        $canCurrentUserEvaluate = $request->currentUser->is_admin
            && TestService::doesTestHaveOpenQuestions($testExecution->test_id);

        return view('test-execution.show')
            ->with('showEvaluateBtn', $canCurrentUserEvaluate)
            ->with('testExecution', $testExecution)
            ->with('questions', TestExecutionService::getTestQuestions($testExecution->test_id));
    }

    /**
     * @uri /testexecution/start/{id}
     * @method GET
     * @param TestExecutionStartRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function start(TestExecutionStartRequest $request, $id)
    {
        date_default_timezone_set('Europe/Sofia');
        $currentUserId = $request->currentUser->id;
        $testExecution = TestExecutionService::findTestExecutionInDb($currentUserId, $id, true);
        $testMaxDuration = Test::findOrFail($id)->max_duration * 60;
        $timeRemainingInSec = !$testExecution
            ? $testMaxDuration
            : ($testMaxDuration - Carbon::now()->diffInSeconds(Carbon::parse($testExecution->start_time)));

        if (!$testExecution) {
            $testExecution = TestExecutionService::startTestExecution($currentUserId, $id);
        }

        return view('test-execution.execute')
            ->with('remainingTime', gmdate('H:i:s', $timeRemainingInSec))
            ->with('timeRemainingInSec', $timeRemainingInSec)
            ->with('testExecutionId', $testExecution->id)
            ->with('questions', TestExecutionService::getExecutionQuestionAnswers($id));
    }

    /**
     * @method POST
     * @uri /testexecution/submit/{id}
     * @param TestExecutionSubmitRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submit(TestExecutionSubmitRequest $request, $id)
    {
        TestExecutionService::updateTestExecution(TestExecution::findOrFail($id));

        return redirect('/tests/index');
    }

    /**
     * @method GET
     * @uri /testexecution/evaluate/{id}
     * @param TestExecutionEvaluateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function evaluate(TestExecutionEvaluateRequest $request, $id)
    {
        $testExecution = TestExecution::findOrFail($id);

        return view('test-execution.evaluate')
            ->with('testExecutionId', $testExecution->id)
            ->with('questions', TestExecutionService::getTestQuestions($testExecution->test_id, true));
    }

    /**
     * @method POST
     * @uri /testexecution/evaluate/{id}
     * @param TestExecutionSubmitEvaluationRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submitEvaluation(TestExecutionSubmitEvaluationRequest $request, $id)
    {
        $testExecution = TestExecution::findOrFail($id);
        $testExecution->result_points += array_sum($request->points);
        $testExecution->save();
        return redirect('/testexecution/index');
    }
}
