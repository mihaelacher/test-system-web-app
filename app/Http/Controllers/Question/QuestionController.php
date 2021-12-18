<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Question\Question;
use App\Models\Question\QuestionAnswer;
use App\Models\Question\QuestionType;
use App\Services\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends AuthController
{
    /**
     * @method GET
     * @uri /questions/index
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('question.index');
    }

    /**
     * @method GET
     * @uri /questions/{id}
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, int $id)
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
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        return view('question.create')
            ->with('questionTypes', QuestionType::all()->sortBy('id'));
    }

    /**
     * @method POST
     * @uri /questions/create
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $currentUserId = Auth::user()->id;
        $isQuestionOpen = $request->is_open;

        $questionId = QuestionService::storeQuestion($request->text, $request->instruction, $request->points, $request->type,
            $request->max_markable_answers, $isQuestionOpen, $currentUserId);

        if (!$isQuestionOpen) {
            QuestionService::storeQuestionAnswers($request->order_num, $request->value, $request->is_correct,
                $currentUserId, $questionId);
        }

        return redirect('/questions');
    }
}
