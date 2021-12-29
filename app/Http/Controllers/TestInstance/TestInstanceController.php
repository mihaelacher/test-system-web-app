<?php

namespace App\Http\Controllers\TestInstance;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\TestInstance\TestInstanceStartExecutionRequest;
use App\Services\TestExecutionService;

class TestInstanceController extends AuthController
{
    /**
     * @method POST
     * @uri /testinstance/{id}/startExecution
     * @param TestInstanceStartExecutionRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function startExecution(TestInstanceStartExecutionRequest $request, $id)
    {
        $testExecutionId = TestExecutionService::createTestExecution($request->currentUser->id, $id);

        return redirect('/testexecution/' . $testExecutionId . '/execute');
    }
}
