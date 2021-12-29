<?php

namespace App\Http\Requests\User;

use App\Http\Requests\MainGetRequest;
use App\Models\Authorization\User;

class UserShowRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('view', User::findOrFail(request()->id));
    }
}
