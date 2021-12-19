<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\Test\TestCreateRequest;
use App\Http\Requests\Test\TestEditRequest;
use App\Http\Requests\Test\TestIndexRequest;
use App\Http\Requests\Test\TestShowRequest;
use App\Http\Requests\Test\TestStoreRequest;
use App\Http\Requests\Test\TestUpdateRequest;
use App\Models\Test\Test;
use App\Models\Test\TestQuestions;
use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('test.index')
            ->with('currentUser', $request->currentUser);
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
        $testHasQuestions = TestQuestions::where('test_id', '=', $id)->count();

        return view('test.show')
            ->with('test', Test::findOrFail($id))
            ->with('hasQuestions', $testHasQuestions)
            ->with('currentUser', $request->currentUser);
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
     * @uri /tests/create
     * @param TestStoreRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(TestStoreRequest $request)
    {
        $currentUserId = Auth::user()->id;

        $testId = TestService::storeTest($request->name, $request->intro_text, $request->max_duration,
            $request->is_visible_for_admins, $currentUserId);

        if (isset($request->selected_question_ids)) {
            $questionIds = array_unique(explode(',',  $request->selected_question_ids));
            TestService::mapQuestionToTest($testId, array_unique($questionIds));
        }
        return redirect('tests/index');
    }

    /**
     * @method GET
     * @uri /tests/edit/{id}
     * @param TestEditRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(TestEditRequest $request, $id)
    {
        $testHasQuestions = TestQuestions::where('test_id', '=', $id)->count();

        return view('test.edit')
            ->with('test', Test::findOrFail($id))
            ->with('hasQuestions', $testHasQuestions);
    }

    /**
     * @method POST
     * @uri /tests/update/{id}
     * @param TestUpdateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(TestUpdateRequest $request, $id)
    {
        TestService::updateTest(Test::findOrFail($id), $request);

        if (isset($request->selected_question_ids)) {
            $questionIds = array_unique(explode(',',  $request->selected_question_ids));
            TestService::mapQuestionToTest($id, array_unique($questionIds));
        }

        return redirect('tests/' . $id);
    }

    /**
     * @method DELETE
     * @uri /{id}
     * @param Request $request
     * @return void
     */
    public function delete(Request $request, $id)
    {

    }

    /**
     * @method GET
     * @uri /tests/inviteUsers/{id}
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function inviteUsers(TestCreateRequest $request, $id)
    {
        return view('test.invite-users')
            ->with('test', Test::findOrFail($id));
    }

    /**
     * @method Post
     * @uri /tests/storeInvitations/{id}
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeInvitations(TestStoreRequest $request, $id)
    {
        $activeFrom = Carbon::parse($request->active_from);
        $activeTo = Carbon::parse($request->active_to);

        $testInstanceId = TestService::createTestInstance($id, $activeFrom, $activeTo, $request->currentUser->id);
        TestService::mapUserToTest($testInstanceId, array_unique(explode(',',  $request->selected_user_ids)));

        return redirect('/tests/index');
    }
}
