<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Test\Test;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AjaxController extends AuthController
{
    /**
     * @method GET
     * @uri /ajax/tests/getTests
     * @param Request $request
     * @return void
     * @throws \Exception
     */
    public function getTestsDataTable(Request $request)
    {
        $questionsQuery = Test::query()
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
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function loadQuestions(Request $request)
    {
        return view('question.index-table')
            ->with('includeCheckbox', true);
    }
}
