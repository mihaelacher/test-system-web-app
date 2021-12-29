<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainFormRequest;
use App\Models\Test\TestExecution;

class TestExecutionSubmitEvaluationRequest extends MainFormRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('evaluate', TestExecution::findOrFail(request()->id));
    }
}
