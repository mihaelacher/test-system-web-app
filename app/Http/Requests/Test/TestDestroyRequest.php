<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainFormRequest;

class TestDestroyRequest extends MainFormRequest
{
    use AuthorizeAdminRequestTrait;
}
