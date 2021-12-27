<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\MainGetRequest;

class TestIndexRequest extends MainGetRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return !is_null($this->currentUser);
    }
}
