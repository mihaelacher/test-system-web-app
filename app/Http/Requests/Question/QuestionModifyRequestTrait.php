<?php

namespace App\Http\Requests\Question;

use App\Models\Question\Question;
use App\Services\QuestionService;

trait QuestionModifyRequestTrait
{
    public function questionModifyAuthorize(): bool
    {
        $question = Question::findOrFail(request()->id);

        return $question->created_by === $this->currentUser->id
            && !QuestionService::belongsQuestionToTestExecution($question->id);
    }
}
