<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\MainGetRequest;
use App\Models\Test\Test;

class TestShowRequest extends MainGetRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
       return $this->currentUser->can('view', Test::findOrFail(request()->id));
    }
}
