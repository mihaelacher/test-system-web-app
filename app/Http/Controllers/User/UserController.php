<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserShowRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\Authorization\User;
use App\Services\UserService;

class UserController extends AuthController
{
    /**
     * @method GET
     * @uri /users/index
     * @param UserIndexRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(UserIndexRequest $request)
    {
        return view('user.index');
    }

    /**
     * @method GET
     * @uri /users/{id}
     * @param UserShowRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(UserShowRequest $request, $id)
    {
        return view('user.show')
            ->with('user', User::findOrFail($id));
    }

    /**
     * @method GET
     * @uri users/edit/{id}
     * @param UserEditRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(UserEditRequest $request, $id)
    {
        return view('user.edit')
            ->with('user', User::findOrFail($id));
    }

    /**
     * @method POST
     * @uri /users/update/{id}
     * @param UserUpdateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UserUpdateRequest $request, $id)
    {
        UserService::updateUser(User::findOrFail($id), $request->first_name, $request->last_name, $request->username,
            $request->email, $request->is_admin);

        return redirect('users/' . $id);
    }

    /**
     * @method GET
     * @uri /users/create
     * @param UserCreateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(UserCreateRequest $request)
    {
        return view('user.create');
    }

    /**
     * @uri /users/create
     * @method POST
     * @param UserStoreRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(UserStoreRequest $request)
    {
        UserService::updateUser(new User(), $request->first_name, $request->last_name, $request->username,
            $request->email, $request->is_admin);

        return redirect('/users/index');
    }

    /**
     * @uri /users/changePassword/{id}
     * @method GET
     * @param UserEditRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function changePassword(UserEditRequest $request, $id)
    {
        return view('user.change-password')
            ->with('userId', $id);
    }

    /**
     * @method POST
     * @uri /users/changePassword/{id}
     * @param UserStoreRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storePassword(UserStoreRequest $request, $id)
    {
        UserService::changeUserPassword(User::findOrFail($id), $request->password);
        return redirect('/users/index');
    }
}
