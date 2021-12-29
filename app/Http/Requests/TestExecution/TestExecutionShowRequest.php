<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainGetRequest;
use App\Models\Test\TestExecution;

class TestExecutionShowRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('view', TestExecution::findOrFail(request()->id));
    }
}
