<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainGetRequest;
use App\Models\Question\Question;
use App\Services\QuestionService;

class QuestionEditRequest extends MainGetRequest
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
