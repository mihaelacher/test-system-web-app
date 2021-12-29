<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainGetRequest;
use App\Models\Test\TestExecution;
class TestExecutionStartRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('execute', TestExecution::findOrFail(request()->id));
    }
}
