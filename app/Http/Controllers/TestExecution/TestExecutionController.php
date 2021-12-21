<?php

namespace App\Http\Controllers\TestExecution;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Test\Test;
use App\Models\Test\TestExecution;
use App\Services\TestExecutionService;
use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function redirect;
use function view;

class TestExecutionController extends AuthController
{

    public function index(Request $request)
    {
        return view('test-execution.index');
    }

    public function show(Request $request, $id)
    {
        $testExecution = TestExecution::findOrFail($id);
        $canCurrentUserEvaluate = Auth::user()->is_admin
            && TestService::doesTestHaveOpenQuestions($testExecution->test_id);

        return view('test-execution.show')
            ->with('showEvaluateBtn', $canCurrentUserEvaluate)
            ->with('testExecution', $testExecution)
            ->with('questions', TestExecutionService::getTestQuestions($testExecution->test_id));
    }

    /**
     * @uri /tests/execute/{id}
     * @method GET
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function start(Request $request, $id)
    {
        date_default_timezone_set('Europe/Sofia');
        $currentUser = Auth::user();
        $testExecution = TestExecutionService::findTestExecutionInDb($currentUser->id, $id, true);
        $testMaxDuration = Test::findOrFail($id)->max_duration * 60;
        $timeRemainingInSec = !$testExecution
            ? $testMaxDuration
            : ($testMaxDuration - Carbon::now()->diffInSeconds(Carbon::parse($testExecution->start_time)));

        if (!$testExecution) {
            $testExecution = TestExecutionService::startTestExecution($currentUser->id, $id);
        }

        return view('test-execution.execute')
            ->with('remainingTime', gmdate('H:i:s', $timeRemainingInSec))
            ->with('timeRemainingInSec', $timeRemainingInSec)
            ->with('testExecutionId', $testExecution->id)
            ->with('questions', TestExecutionService::getExecutionQuestionAnswers($id));
    }

    /**
     * @method POST
     * @uri /tests/execute/{id}
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function finish(Request $request, $id)
    {
        TestExecutionService::updateTestExecution(TestExecution::findOrFail($id));

        return redirect('/tests/index');
    }

    /**
     * @method GET
     * @uri /tests/execute/evaluate/{id}
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function evaluate(Request $request, $id)
    {
        $testExecution = TestExecution::findOrFail($id);

        return view('test-execution.evaluate')
            ->with('testExecutionId', $testExecution->id)
            ->with('questions', TestExecutionService::getTestQuestions($testExecution->test_id, true));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submit(Request $request, $id)
    {
        $testExecution = TestExecution::findOrFail($id);
        $testExecution->result_points += array_sum($request->points);
        $testExecution->save();
        return redirect('/tests/execute/index');
    }
}
