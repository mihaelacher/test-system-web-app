<?php

namespace App\Http\Requests\User;

use App\Http\Requests\MainFormRequest;
use App\Models\Authorization\User;

class UserDestroyRequest extends MainFormRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('delete', User::class);
    }
}
