<?php

namespace App\Http\Requests\Test;

use App\Models\Test\Test;

class TestStoreRequest extends TestValidateRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('create', Test::class);
    }
}
