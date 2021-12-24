<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainFormRequest;

class QuestionDestroyRequest extends MainFormRequest
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