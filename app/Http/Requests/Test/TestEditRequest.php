<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainGetRequest;
use App\Models\Test\Test;
use App\Services\TestService;

class TestEditRequest extends MainGetRequest
{
    use AuthorizeAdminRequestTrait{
        authorize as adminAuthorize;
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->adminAuthorize()
            && TestService::canTestBeModified(Test::findOrFail(request()->id), $this->currentUser->id);
    }
}
