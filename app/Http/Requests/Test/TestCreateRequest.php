<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainGetRequest;

class TestCreateRequest extends MainGetRequest
{
    use AuthorizeAdminRequestTrait;
}
