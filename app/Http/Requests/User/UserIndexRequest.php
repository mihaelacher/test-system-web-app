<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainGetRequest;

class UserIndexRequest extends MainGetRequest
{
    use AuthorizeAdminRequestTrait;
}
