<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\Question\QuestionCreateRequest;
use App\Http\Requests\Question\QuestionDestroyRequest;
use App\Http\Requests\Question\QuestionEditRequest;
use App\Http\Requests\Question\QuestionIndexRequest;
use App\Http\Requests\Question\QuestionShowRequest;
use App\Http\Requests\Question\QuestionStoreRequest;
use App\Http\Requests\Question\QuestionUpdateRequest;
use App\Models\Question\Question;
use App\Models\Question\QuestionType;
use App\Services\QuestionService;

class QuestionController extends AuthController
{
    /**
     * @method GET
     * @uri /questions/index
     * @param QuestionIndexRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(QuestionIndexRequest $request)
    {
        return view('question.index');
    }

    /**
     * @method GET
     * @uri /questions/{id}
     * @param QuestionShowRequest $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(QuestionShowRequest $request, int $id)
    {
        $question = Question::findOrFail($id);
        $canEdit = $question->created_by === $request->currentUser->id
            && !QuestionService::belongsQuestionToTestExecution($id);

        return view('question.show')
            ->with('question', $question)
            ->with('canEdit', $canEdit);
    }

    /**
     * @method GET
     * @uri /questions/create
     * @param QuestionCreateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(QuestionCreateRequest $request)
    {
        return view('question.create')
            ->with('questionTypes', QuestionType::all()->sortBy('id'));
    }

    /**
     * @method POST
     * @uri /questions/store
     * @param QuestionStoreRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(QuestionStoreRequest $request)
    {
        QuestionService::handleQuestionOperations($request);
        return redirect('/questions/index');
    }

    /**
     * @method GET
     * @uri /questions/{id}/edit
     * @param QuestionEditRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(QuestionEditRequest $request, $id)
    {
      return view('question.edit')
            ->with('questionTypes', QuestionType::all()->sortBy('id'))
            ->with('question', Question::findOrFail($id))
            ->with('canEdit', QuestionService::belongsQuestionToTestExecution($id));
    }

    /**
     * @method POST
     * @uri /questions/{id}/update
     * @param QuestionUpdateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(QuestionUpdateRequest $request, $id)
    {
        QuestionService::handleQuestionOperations($request, $id);
        return redirect('/questions/' . $id);
    }

    /**
     * @method DELETE
     * @uri /questions/{id}/delete
     * @param QuestionDestroyRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(QuestionDestroyRequest $request, $id)
    {
        QuestionService::destroyQuestion($id);
        return redirect('/questions/index');
    }
}
