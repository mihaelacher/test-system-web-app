<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainFormRequest;

class TestExecutionSubmitEvaluationRequest extends MainFormRequest
{
    use AuthorizeAdminRequestTrait;
}
