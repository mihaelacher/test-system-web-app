<?php

namespace App\Http\Requests\User;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainFormRequest;

class UserStoreRequest extends MainFormRequest
{
    use AuthorizeAdminRequestTrait, ValidateUserRequestTrait;
}
