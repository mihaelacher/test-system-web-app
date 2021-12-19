<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\Question\QuestionCreateRequest;
use App\Http\Requests\Question\QuestionEditRequest;
use App\Http\Requests\Question\QuestionIndexRequest;
use App\Http\Requests\Question\QuestionShowRequest;
use App\Http\Requests\Question\QuestionStoreRequest;
use App\Http\Requests\Question\QuestionUpdateRequest;
use App\Models\Question\Question;
use App\Models\Question\QuestionAnswer;
use App\Models\Question\QuestionType;
use App\Services\QuestionService;
use Illuminate\Support\Facades\Auth;

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
        $questionData = Question::join('question_types as qt',
            'qt.id', '=', 'questions.question_type_id')
            ->where('questions.id', '=', $id)
            ->select([
                'questions.*',
                'qt.name as type',
            ])
            ->first();

        $answers = null;
        if (!$questionData->is_open) {
            $answers = QuestionAnswer::where('question_id', '=', $id)
                ->orderBy('order_num')
                ->get();
        }

        return view('question.show')
            ->with('question', $questionData)
            ->with('answers', $answers);
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
     * @uri /questions/create
     * @param QuestionStoreRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(QuestionStoreRequest $request)
    {
        $currentUserId = $request->currentUser->id;
        $isQuestionOpen = $request->is_open;

        $questionId = QuestionService::storeQuestion($request->text, $request->instruction, $request->points, $request->type,
            $request->max_markable_answers, $isQuestionOpen, $currentUserId);

        if (!$isQuestionOpen) {
            QuestionService::storeQuestionAnswers($request->order_num, $request->value, $request->is_correct,
                $currentUserId, $questionId);
        }

        return redirect('/questions');
    }

    /**
     * @method GET
     * @uri /questions/edit/{id}
     * @param QuestionEditRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(QuestionEditRequest $request, $id)
    {
        $question = Question::findOrFail($id);
        $answers = QuestionAnswer::where('question_id', '=', $id)->orderBy('order_num')->get();

        return view('question.edit')
            ->with('questionTypes', QuestionType::all()->sortBy('id'))
            ->with('question', $question)
            ->with('answers', $answers);
    }

    /**
     * @method POST
     * @uri /questions/update/{id}
     * @param QuestionUpdateRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(QuestionUpdateRequest $request, $id)
    {
        QuestionService::updateQuestion(Question::findOrFail($id), $request);
        if (!$request->is_open) {
            QuestionService::updateQuestionAnswers($request->answer_id, $request->order_num, $request->value, $request->is_correct);
        }

        return redirect('/questions/' . $id);
    }
}
