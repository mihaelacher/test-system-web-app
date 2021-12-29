<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainFormRequest;
use App\Models\Test\TestExecution;

class TestExecutionSubmitRequest extends MainFormRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('submit', TestExecution::findOrFail(request()->id));
    }
}
