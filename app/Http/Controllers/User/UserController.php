<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserShowRequest;
use App\Http\Requests\User\UserStorePasswordRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\Authorization\User;
use App\Services\UserService;
use App\Util\MessageUtil;

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
     * @uri users/{id}/edit
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
     * @uri /users/{id}/update
     * @param UserUpdateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UserUpdateRequest $request, $id)
    {
        UserService::setUserAttributes(User::findOrFail($id), $request->first_name, $request->last_name,
            $request->email, $request->is_admin, $request->username);

        MessageUtil::success('You have successfully updated the user!');

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
     * @uri /users/store
     * @method POST
     * @param UserStoreRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(UserStoreRequest $request)
    {
        UserService::setUserAttributes(new User(), $request->first_name, $request->last_name,
            $request->email, $request->is_admin, $request->username);

        MessageUtil::success('You have successfully created the user!');

        return redirect('/users/index');
    }

    /**
     * @uri /users/{id}/changePassword
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
     * @uri /users/{id}/storePassword
     * @param UserStorePasswordRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storePassword(UserStorePasswordRequest $request, $id)
    {
        UserService::changeUserPassword(User::findOrFail($id), $request->password);

        MessageUtil::success('You have successfully changed the user\'s password!');
        return redirect('/users/index');
    }
}
