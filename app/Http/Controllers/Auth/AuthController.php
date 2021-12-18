<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
