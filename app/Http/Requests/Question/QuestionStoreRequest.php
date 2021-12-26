<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\AuthorizeAdminRequestTrait;

class QuestionStoreRequest extends ValidateQuestionRequest
{
    use AuthorizeAdminRequestTrait;
}
