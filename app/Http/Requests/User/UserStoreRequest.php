<?php

namespace App\Http\Requests\User;

use App\Http\Requests\MainFormRequest;
use App\Models\Authorization\User;

class UserStoreRequest extends MainFormRequest
{
    use ValidateUserRequestTrait;

    public function authorize(): bool
    {
        return $this->currentUser->can('create', User::class);
    }
}
