<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\MainGetRequest;
use App\Models\Question\Question;

class QuestionIndexRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('viewAny', Question::class);
    }
}
