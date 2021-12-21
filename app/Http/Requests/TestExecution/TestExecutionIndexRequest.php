<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\MainGetRequest;

class TestExecutionIndexRequest extends MainGetRequest
{

    public function authorize()
    {
        return $this->currentUser;
    }
}
