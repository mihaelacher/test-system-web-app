<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\MainFormRequest;
use App\Models\Question\Question;

class QuestionDestroyRequest extends MainFormRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('delete', Question::findOrFail(request()->id));
    }
}
