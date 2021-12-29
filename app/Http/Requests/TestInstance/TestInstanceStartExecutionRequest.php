<?php

namespace App\Http\Requests\TestInstance;

use App\Http\Requests\MainFormRequest;
use App\Models\Test\TestInstance;

class TestInstanceStartExecutionRequest extends MainFormRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('startExecution', TestInstance::findOrFail(request()->id));
    }
}
