<?php

namespace App\Http\Controllers\TestExecution;

use App\Exceptions\TestExecutionAnswerUpdateException;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\TestExecution\TestExecutionIndexRequest;
use App\Http\Requests\TestExecution\TestExecutionSubmitAnswerRequest;
use App\Models\Question\QuestionType;
use App\Services\TestExecutionService;
use App\Util\LogUtil;
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
        $questionTypeId = $request->questionTypeId;

        try {
            $testExecutionAnswer =
                TestExecutionService::getTestExecutionAnswer($testExecutionId, $questionId);

            switch ($questionTypeId) {
                case QuestionType::TEXT_SHORT:
                    $testExecutionAnswer->response_text_short = $inputText;
                    break;
                case QuestionType::TEXT_LONG:
                    $testExecutionAnswer->response_text_long = $inputText;
                    break;
                case QuestionType::NUMERIC:
                    $testExecutionAnswer->response_numeric = $inputText;
                    break;
            }

            $testExecutionAnswer->save();
            return 1;
        } catch (TestExecutionAnswerUpdateException $e) {
            LogUtil::logError($e->getMessage());
            return 0;
        }
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

        try {
            $existingTestExecutionAnswer =
                TestExecutionService::findExistingTestExecutionAnswerInDb($testExecutionId, $questionId, $answerId);

            // case 1: user unchecked already submitted answer
            if (!is_null($existingTestExecutionAnswer) && !$isChecked) {
                // delete the connected answer to db
                $existingTestExecutionAnswer->delete();
            } // case 2: user tries to mark more correct answers than allowed
            else if (
                $isChecked
                && is_null($existingTestExecutionAnswer)
                && TestExecutionService::isMaxMarkableAnswersLimitExceeded($testExecutionId, $questionId)
            ) {
                // flash error message
                return 0;
            } // case 3: no answers given, insert the new one
            else if (is_null($existingTestExecutionAnswer)) {
                $answer = TestExecutionService::createTestExecutionAnswer($testExecutionId, $questionId);
                $answer->question_answer_id = $answerId;
                $answer->save();
            }
        } catch (TestExecutionAnswerUpdateException $e) {
            LogUtil::logError($e->getMessage());
            return -1;
        }
        // flash success message
        return 1;
    }
}
