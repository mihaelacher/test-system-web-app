<?php

namespace App\Http\Requests\Question;

use App\Models\Question\Question;

class QuestionUpdateRequest extends ValidateQuestionRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('update', Question::findOrFail(request()->id));
    }
}
