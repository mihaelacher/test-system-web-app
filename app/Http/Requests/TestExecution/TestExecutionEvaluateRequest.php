<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainGetRequest;
use App\Models\Test\TestExecution;

class TestExecutionEvaluateRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('evaluate', TestExecution::findOrFail(request()->id));
    }
}
