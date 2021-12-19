<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Authorization\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AjaxController extends AuthController
{
    /**
     * @method GET
     * @uri /ajax/users/getUsers
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getUsersDatatable(Request $request)
    {
        $questionsQuery = User::query()
            ->select([
                'id',
                DB::raw('CONCAT_WS(" ", first_name, last_name) as full_name'),
                'username',
                'email',
                DB::raw('IF(is_admin = 1, "Yes", "No") as is_admin')
            ]);

        $table = DataTables::of($questionsQuery)
            ->editColumn('full_name', '<a href="/users/{{$id}}"> {{ $full_name }} </a>');

        return $table->rawColumns(['full_name'])->make(true);
    }
}
