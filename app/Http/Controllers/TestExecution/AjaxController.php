<?php

namespace App\Http\Controllers\TestExecution;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\TestExecution\TestExecutionIndexRequest;
use App\Http\Requests\TestExecution\TestExecutionSubmitAnswerRequest;
use App\Services\TestExecutionService;
use App\Util\MessageUtil;
use Yajra\DataTables\DataTables;

class AjaxController extends AuthController
{
    /**
     * @method GET
     * @uri /ajax/testexecution/getTestExecutions
     * @param TestExecutionIndexRequest $request
     * @return void
     * @throws \Exception
     */
    public function getTestExecutionsDataTable(TestExecutionIndexRequest $request)
    {
        $currentUser = $request->currentUser;

        $questionsQuery = TestExecutionService::getTestExecutionsIndexQueryBasedOnCurrentUser($currentUser)
            ->select([
                'test_executions.id', 'name', 'start_time', 'end_time', 'result_points'
            ]);

        $table = DataTables::of($questionsQuery)
            ->editColumn('name', '<a href="/testexecution/{{$id}}/show"> {{ $name }} </a>');

        return $table->rawColumns(['name'])->make(true);
    }

    /**
     * @method POST
     * @uri /ajax/testexecution/submitOpenQuestion/{id}
     * @param TestExecutionSubmitAnswerRequest $request
     * @return int
     */
    public function submitOpenQuestion(TestExecutionSubmitAnswerRequest $request): int
    {
        $testExecutionId = $request->id;
        $questionId = $request->questionId;
        $inputText = $request->inputValue;

        $existingTestExecutionAnswer =
            TestExecutionService::findExistingTestExecutionAnswerInDb($testExecutionId, $questionId);

        if ($existingTestExecutionAnswer) {
            $existingTestExecutionAnswer->response_text_short = $inputText;
            $existingTestExecutionAnswer->save();
        } else {
            TestExecutionService::insertTestExecutionAnswer($testExecutionId, $questionId, null, $inputText);
        }
        return 1;
    }

    /**
     * @method POST
     * @uri /ajax/testexecution/submitQuestionAnswer/{id}
     * @param TestExecutionSubmitAnswerRequest $request
     * @return int
     */
    public function submitQuestionAnswer(TestExecutionSubmitAnswerRequest $request): int
    {
        $testExecutionId = $request->id;
        $questionId = $request->questionId;
        $answerId = $request->answerId;
        $isChecked = $request->isChecked;

        $existingTestExecutionAnswer =
            TestExecutionService::findExistingTestExecutionAnswerInDb($testExecutionId, $questionId, $answerId);

        if ($existingTestExecutionAnswer && !$isChecked) {
            $existingTestExecutionAnswer->delete();
        } else if(
            $isChecked
            && is_null($existingTestExecutionAnswer)
            && TestExecutionService::isMaxMarkableAnswersLimitExceeded($testExecutionId, $questionId)
        ) {
            return 0;
        }
        else if (is_null($existingTestExecutionAnswer)){
            TestExecutionService::insertTestExecutionAnswer($testExecutionId, $questionId, $answerId);
        }
        return 1;
    }
}
