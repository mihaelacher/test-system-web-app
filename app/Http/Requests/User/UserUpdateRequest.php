<?php

namespace App\Http\Requests\User;

use App\Http\Requests\MainFormRequest;
use App\Models\Authorization\User;

class UserUpdateRequest extends MainFormRequest
{
    use ValidateUserRequestTrait;

    public function authorize(): bool
    {
        return $this->currentUser->can('update', User::findOrFail(request()->id));
    }
}
