<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainGetRequest;

class QuestionShowRequest extends MainGetRequest
{
    use AuthorizeAdminRequestTrait;
}
