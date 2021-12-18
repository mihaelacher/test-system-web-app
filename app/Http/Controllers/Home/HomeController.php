<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;

class HomeController extends AuthController
{
    /**
     * @method GET
     * @uri /
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function home(Request $request)
    {
        return view('home.welcome');
    }
}
