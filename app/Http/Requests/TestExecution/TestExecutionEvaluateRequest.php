<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainGetRequest;

class TestExecutionEvaluateRequest extends MainGetRequest
{
    use AuthorizeAdminRequestTrait;
}
