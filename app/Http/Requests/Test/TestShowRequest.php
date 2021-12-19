<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\MainGetRequest;

class TestShowRequest extends MainGetRequest
{

    public function authorize()
    {
        // TODO: Implement authorize() method specific for Test
        return true;
    }
}
