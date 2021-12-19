<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Question\Question;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AjaxController extends AuthController
{
    /**
     * @param Request $request
     * @return void
     * @method GET
     * @uri /ajax/questions/getQuestions
     * @throws \Exception
     */
    public function getQuestionsDataTable(Request $request)
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
