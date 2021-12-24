<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainFormRequest;

class QuestionStoreRequest extends MainFormRequest
{
    use AuthorizeAdminRequestTrait;
}
