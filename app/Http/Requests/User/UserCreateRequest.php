<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainGetRequest;

class UserCreateRequest extends MainGetRequest
{
    use AuthorizeAdminRequestTrait;
}
