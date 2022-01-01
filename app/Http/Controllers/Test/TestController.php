<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\Test\StoreTestInvitationsRequest;
use App\Http\Requests\Test\TestCreateRequest;
use App\Http\Requests\Test\TestDestroyRequest;
use App\Http\Requests\Test\TestEditRequest;
use App\Http\Requests\Test\TestIndexRequest;
use App\Http\Requests\Test\TestShowRequest;
use App\Http\Requests\Test\TestStoreRequest;
use App\Http\Requests\Test\TestUpdateRequest;
use App\Models\Test\Test;
use App\Services\TestService;
use App\Util\MessageUtil;

class TestController extends AuthController
{
    /**
     * @method GET
     * @uri /tests/index
     * @param TestIndexRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(TestIndexRequest $request)
    {
        return view('test.index');
    }

    /**
     * @method GET
     * @uri /{id}
     * @param TestShowRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(TestShowRequest $request, $id)
    {
        return view('test.show')
            ->with('test', Test::findOrFail($id));
    }

    /**
     * @method GET
     * @uri /tests/create
     * @param TestCreateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(TestCreateRequest $request)
    {
        return view('test.create');
    }

    /**
     * @method POST
     * @uri /tests/store
     * @param TestStoreRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(TestStoreRequest $request)
    {
        TestService::handleTestOperations(new Test(), $request);

        MessageUtil::success('You\'ve successfully created the test!');

        return redirect('tests/index');
    }

    /**
     * @method GET
     * @uri /tests/{id}/edit
     * @param TestEditRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(TestEditRequest $request, $id)
    {
        return view('test.edit')
            ->with('test', Test::findOrFail($id));
    }

    /**
     * @method POST
     * @uri /tests/{id}/update
     * @param TestUpdateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(TestUpdateRequest $request, $id)
    {
        TestService::handleTestOperations(Test::findOrFail($id), $request);

        MessageUtil::success('You\'ve successfully updated the test!');

        return redirect('tests/' . $id);
    }

    /**
     * @method POST
     * @uri tests/{id}/delete
     * @param TestDestroyRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(TestDestroyRequest $request, $id)
    {
        TestService::destroyTest($id);

        MessageUtil::success('You\'ve successfully deleted the test!');

        return redirect('tests/index');
    }

    /**
     * @method GET
     * @uri /tests/{id}/inviteUsers
     * @param TestCreateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function inviteUsers(TestCreateRequest $request, $id)
    {
        return view('test.invite-users')
            ->with('test', Test::findOrFail($id));
    }

    /**
     * @method POST
     * @uri /tests/{id}/storeInvitations/
     * @param StoreTestInvitationsRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeInvitations(StoreTestInvitationsRequest $request, $id)
    {
        TestService::handleTestInvitations($id, $request);

        MessageUtil::success('You\'ve successfully invited users to the test!');

        return redirect('/tests/index');
    }
}
