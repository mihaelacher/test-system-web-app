<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\Test\TestCreateRequest;
use App\Http\Requests\Test\TestIndexRequest;
use App\Models\Question\Question;
use App\Models\Test\Test;
use App\Services\TestService;
use App\Util\IconProvider;
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

        $questionsQuery = TestService::getTestIndexQueryBasedOnLoggedUser($currentUser);

        $table = DataTables::of($questionsQuery);

        $table->editColumn('name', function ($test) use ($currentUser) {
            $testModel = Test::find($test->id);

            if ($currentUser->can('view', $testModel)) {
                return '<a href="/tests/' . $testModel->id . '">' . $testModel->name . '</a>';
            }
            return $test->name;
        });

        $table->addColumn('operations', function ($test) use ($currentUser) {
            $testModel = Test::find($test->id);
            $btn = '';

            if ($currentUser->can('seeModal', $testModel)) {
                $btn .= '<button type="button" class="btn-success btn-xs testInfoModalBtn" data-toggle="modal"
                data-target="#testInfoModal" data-test_id="' . $testModel->id . '">' . IconProvider::INFO . '</button>';
            }

            if ($currentUser->can('startExecution', $testModel)) {
                $testInstance = TestService::findExistingTestInstanceInDB($currentUser, $testModel->id, true);

                $btn .= '<form style="display:inline" method="POST" action="/testinstance/' . $testInstance->id . '/startExecution">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button title="EXECUTE" type="submit" class="btn-info btn-xs">' . IconProvider::START . '</button>
                        </form>';
            }

            if ($currentUser->can('update', $testModel)) {
                $btn .= ('<a class="btn-primary btn-sm" href="/test/' . $testModel->id . '/edit">'
                    . IconProvider::EDIT . '</a>');
            }

            if ($currentUser->can('delete', $testModel)) {
                $btn .= ('<form style="display:inline" method="POST" action="/tests/' . $testModel->id . '/delete">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button type="submit" class="btn-danger btn-xs">' . IconProvider::DELETE . '</button>
                        </form>');
            }

            return $btn;
        });

        return $table->rawColumns(['name', 'operations'])->make(true);
    }


    public function getModal(TestIndexRequest $request, $id)
    {
        $test = Test::join('test_instances as ti', 'ti.test_id', '=', 'tests.id')
            ->join('test_has_visible_users as thvu', 'thvu.test_instance_id', '=', 'ti.id')
            ->where('tests.id', '=', $id)
            ->where('thvu.user_id', '=', $request->currentUser->id)
            ->select([
                'tests.name', 'tests.intro_text', 'tests.max_duration',
                'ti.active_from', 'ti.active_to'
            ])->first();

        return view('test.blocks.test-modal-block')
            ->with('test', $test);
    }

    /**
     * @uri GET
     * @uri /ajax/tests/{id}/getTestQuestions
     * @param TestCreateRequest $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function getTestQuestions(TestCreateRequest $request, $id)
    {
        $isEditMode = $request->isEdit;

        $questionsQuery = Question::join('question_types as qt', 'qt.id', '=', 'questions.question_type_id')
            ->leftJoin('test_questions as tq', function ($join) use ($id) {
                $join->on('tq.question_id', '=', 'questions.id')
                    ->where('tq.test_id', '=', $id);
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
