<?php

namespace App\Http\Requests\TestExecution;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainGetRequest;
use App\Models\Test\TestExecution;

class TestExecutionShowRequest extends MainGetRequest
{
    use AuthorizeAdminRequestTrait {
        authorize as traitAuthorize;
    }

    public function authorize()
    {
        return $this->traitAuthorize()
            || TestExecution::findOrFail(request()->id)->user_id === $this->currentUser->id;
    }
}
