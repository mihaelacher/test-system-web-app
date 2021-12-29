<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\User\UserIndexRequest;
use App\Models\Authorization\User;
use App\Util\IconProvider;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AjaxController extends AuthController
{
    /**
     * @method GET
     * @uri /ajax/users/getUsers
     * @param UserIndexRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function getUsersDatatable(UserIndexRequest $request)
    {
        $currentUser = $request->currentUser;
        $showOperationsCol = $request->showOperations;

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

        if($showOperationsCol) {
            $table->addColumn('operations', function ($user) use ($currentUser) {
                $userModel = User::find($user->id);
                $btn = '';

                if ($currentUser->can('update', $userModel)) {
                    $btn .= ('<a class="btn-primary btn-sm" href="/users/' . $userModel->id . '/edit">'
                        . IconProvider::EDIT . '</a>');
                }

                if ($currentUser->can('delete', $userModel)) {
                    $btn .= ('<form style="display:inline" method="POST" action="/users/' . $userModel->id . '/delete">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button type="submit" class="btn-danger btn-xs">' . IconProvider::DELETE . '</button>
                        </form>');
                }

                return $btn;
            });
        }

        return $table->rawColumns(['full_name', 'operations'])->make(true);
    }
}
