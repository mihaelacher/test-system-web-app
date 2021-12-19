<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Authorization\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends AuthController
{
    /**
     * @method GET
     * @uri /users/index
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('user.index');
    }

    /**
     * @method GET
     * @uri /users/{id}
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, $id)
    {
        return view('user.show')
            ->with('user', User::findOrFail($id));
    }

    /**
     * @method GET
     * @uri users/edit/{id}
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $id)
    {
        return view('user.edit')
            ->with('user', User::findOrFail($id));
    }

    /**
     * @method POST
     * @uri /users/update/{id}
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        UserService::updateUser(User::findOrFail($id), $request, Auth::user()->id);
        return redirect('users/' . $id);
    }

    /**
     * @method GET
     * @uri /users/create
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        return view('user.create');
    }

    /**
     * @uri /users/create
     * @method POST
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        UserService::createUser($request->first_name, $request->last_name, $request->username, $request->email,
            $request->is_admin, Auth::user()->id);
        return redirect('/users/index');
    }

    /**
     * @uri /users/changePassword/{id}
     * @method GET
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function changePassword(Request $request, $id)
    {
        return view('user.change-password')
            ->with('userId', $id);
    }

    /**
     * @method POST
     * @uri /users/changePassword/{id}
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storePassword(Request $request, $id)
    {
        UserService::changeUserPassword(User::findOrFail($id), $request->password);
        return redirect('/users/index');
    }
}
