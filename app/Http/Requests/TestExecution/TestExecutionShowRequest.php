<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainGetRequest;

class TestExecutionShowRequest extends MainGetRequest
{

    public function authorize()
    {
        // TODO: Implement authorize() method.
        return true;
    }
}
