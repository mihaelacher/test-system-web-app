<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainFormRequest;
use App\Models\Test\TestExecution;

class TestExecutionSubmitAnswerRequest extends MainFormRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('submitAnswer', TestExecution::findOrFail(request()->id));
    }
}
