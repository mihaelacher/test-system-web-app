<?php

namespace App\Http\Requests\User;

use App\Http\Requests\MainGetRequest;
use App\Models\Authorization\User;

class UserEditRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('update', User::findOrFail(request()->id));
    }
}
