<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\MainFormRequest;
use App\Models\Test\Test;

class TestDestroyRequest extends MainFormRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('delete', Test::findOrFail(request()->id));
    }
}
