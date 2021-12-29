<?php

namespace App\Http\Requests\Question;

use App\Models\Question\Question;

class QuestionStoreRequest extends ValidateQuestionRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('create', Question::class);
    }
}
