<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainFormRequest;
use App\Models\Test\TestExecution;

abstract class TestExecutionAuthorizeRequest extends MainFormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return TestExecution::findOrFail(request()->id)->user_id === $this->currentUser->id;
    }
}
