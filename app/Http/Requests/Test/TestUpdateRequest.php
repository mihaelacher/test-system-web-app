<?php

namespace App\Http\Requests\Test;

use App\Models\Test\Test;

class TestUpdateRequest extends TestValidateRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('update', Test::findOrFail(request()->id));
    }
}
