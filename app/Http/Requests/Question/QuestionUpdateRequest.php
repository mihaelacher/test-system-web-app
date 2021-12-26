<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\AuthorizeAdminRequestTrait;

class QuestionUpdateRequest extends ValidateQuestionRequest
{
    use AuthorizeAdminRequestTrait {
        authorize as authorizeAdmin;
    }
    use QuestionModifyRequestTrait;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->authorizeAdmin() && $this->questionModifyAuthorize();
    }
}
