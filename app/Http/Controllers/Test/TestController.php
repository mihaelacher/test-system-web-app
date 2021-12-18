<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Auth\AuthController;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends AuthController
{
    /**
     * @method GET
     * @uri /tests/index
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        return view('test.index');
    }

    /**
     * @method GET
     * @uri /{id}
     * @param Request $request
     * @return void
     */
    public function show(Request $request, $id)
    {

    }

    /**
     * @method GET
     * @uri /tests/create
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        return view('test.create');
    }

    /**
     * @method POST
     * @uri /tests/create
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
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
     * @param Request $request
     * @return void
     */
    public function edit(Request $request, $id)
    {

    }

    /**
     * @method POST
     * @uri /tests/update/{id}
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id)
    {

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
}
