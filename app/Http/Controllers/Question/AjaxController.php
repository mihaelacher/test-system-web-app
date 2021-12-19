<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\Question\QuestionIndexRequest;
use App\Models\Question\Question;
use Yajra\DataTables\DataTables;

class AjaxController extends AuthController
{
    /**
     * @param QuestionIndexRequest $request
     * @return void
     * @throws \Exception
     * @method GET
     * @uri /ajax/questions/getQuestions
     */
    public function getQuestionsDataTable(QuestionIndexRequest $request)
    {
        $questionsQuery = Question::join('question_types as qt', 'qt.id', '=', 'questions.question_type_id')
            ->select([
                'questions.id',
                'questions.text as title',
                'questions.instruction as question',
                'qt.name as type',
                'questions.points'
            ])->orderBy('created_at');

        $table = DataTables::of($questionsQuery)
            ->editColumn('title', '<a href="/questions/{{$id}}"> {{ $title }} </a>');

        return $table->rawColumns(['title'])->make(true);
    }
}
