<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\Question\QuestionIndexRequest;
use App\Models\Question\Question;
use App\Util\IconProvider;
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
        $currentUser = $request->currentUser;

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

        $table->addColumn('operations', function ($question) use ($currentUser) {
            $questionModel = Question::find($question->id);
            $btn = '';

            if ($currentUser->can('update', $questionModel)) {
                $btn .= ('<a class="btn-primary btn-sm" href="/questions/' . $question->id . '/edit">'
                    . IconProvider::EDIT . '</a>');
            }

            if ($currentUser->can('delete', $questionModel)) {
                $btn .= ('<form style="display:inline" method="POST" action="/questions/' . $question->id . '/delete">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button type="submit" class="btn-danger btn-xs">'. IconProvider::DELETE . '</button>
                        </form>');
            }

            return $btn;
        });

        return $table->rawColumns(['title', 'operations'])->make(true);
    }
}
