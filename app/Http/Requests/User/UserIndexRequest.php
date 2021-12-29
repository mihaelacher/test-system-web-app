<?php

namespace App\Http\Requests\User;

use App\Http\Requests\MainGetRequest;
use App\Models\Authorization\User;

class UserIndexRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('viewAny', User::class);
    }
}
