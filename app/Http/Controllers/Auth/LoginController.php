<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\AuthenticateUserTrait;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController
{
    use AuthenticateUserTrait;

    public function __construct()
    {
        $this->middleware('guest')->except('getLogout');
    }

    /**
     * @method GET
     * @uri /auth/login
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
}
