<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainGetRequest;

class QuestionIndexRequest extends MainGetRequest
{
    use AuthorizeAdminRequestTrait;
}
