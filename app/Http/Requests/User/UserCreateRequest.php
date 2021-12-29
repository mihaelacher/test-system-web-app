<?php

namespace App\Http\Requests\User;

use App\Http\Requests\MainGetRequest;
use App\Models\Authorization\User;

class UserCreateRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('create', User::class);
    }
}
