<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\AuthorizeAdminRequestTrait;

class TestStoreRequest extends TestValidateRequest
{
    use AuthorizeAdminRequestTrait;
}
