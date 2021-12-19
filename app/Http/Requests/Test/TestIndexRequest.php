<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\MainGetRequest;

class TestIndexRequest extends MainGetRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
