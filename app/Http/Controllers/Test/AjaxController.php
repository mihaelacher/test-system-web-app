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
                'name', 'intro_text', 'max_duration'
            ]);

        $table = DataTables::of($questionsQuery)
            ->editColumn('name', '<a href="/tests/{{$id}}"> {{ $name }} </a>');

        return $table->rawColumns(['name'])->make(true);
    }
}
