<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\Question\QuestionIndexRequest;
use App\Http\Requests\Test\TestCreateRequest;
use App\Http\Requests\Test\TestIndexRequest;
use App\Models\Question\Question;
use App\Services\TestService;
use Yajra\DataTables\DataTables;

class AjaxController extends AuthController
{
    /**
     * @method GET
     * @uri /ajax/tests/getTests
     * @param TestIndexRequest $request
     * @return void
     * @throws \Exception
     */
    public function getTestsDataTable(TestIndexRequest $request)
    {
        $currentUser = $request->currentUser;

        $questionsQuery = TestService::getTestIndexQueryBasedOnLoggedUser($currentUser)
            ->select([
                'id', 'name', 'intro_text', 'max_duration'
            ]);

        $table = DataTables::of($questionsQuery)
            ->editColumn('name', '<a href="/tests/{{$id}}"> {{ $name }} </a>');

        return $table->rawColumns(['name'])->make(true);
    }

    /**
     * @method GET
     * @uri ajax/tests/loadQuestions
     * @param QuestionIndexRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function loadQuestions(QuestionIndexRequest $request)
    {
        return view('question.index-table')
            ->with('includeCheckbox', true);
    }

    /**
     * @uri GET
     * @uri /ajax/tests/getTestQuestions
     * @param TestCreateRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function getTestQuestions(TestCreateRequest $request)
    {
        $testId = $request->testId;
        $isEditMode = $request->isEditMode;

        $questionsQuery = Question::join('question_types as qt', 'qt.id', '=', 'questions.question_type_id')
            ->leftJoin('test_questions as tq', function ($join) use ($testId) {
                $join->on('tq.question_id', '=', 'questions.id')
                    ->where('tq.test_id', '=', $testId);
            })
            ->select([
                'questions.id',
                'questions.text as title',
                'questions.instruction as question',
                'qt.name as type',
                'questions.points',
                'tq.id as test_question'
            ])->orderBy('created_at');

        if (!isset($isEditMode)) {
            $questionsQuery->whereNotNull('tq.id');
        }

        $table = DataTables::of($questionsQuery)
            ->editColumn('title', '<a href="/questions/{{$id}}"> {{ $title }} </a>');

        if (isset($isEditMode)) {
            $table->setRowClass(function ($question) {
                return $question->test_question ? 'selected' : '';
            });
        }

        return $table->rawColumns(['title'])->make(true);
    }
}
