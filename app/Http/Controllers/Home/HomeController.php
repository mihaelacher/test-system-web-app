<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Test\TestExecution;
use App\Models\Test\TestExecutionAnswer;
use App\Services\TestExecutionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends AuthController
{
    /**
     * @method GET
     * @uri /
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function home(Request $request)
    {/*
        Auth::guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();*/
        return view('home.welcome');
    }
}
