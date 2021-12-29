<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\MainGetRequest;
use App\Models\Test\Test;

class TestEditRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('update', Test::findOrFail(request()->id));
    }
}
