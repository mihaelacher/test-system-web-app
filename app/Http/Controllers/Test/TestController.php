<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;

class TestController extends AuthController
{
    /**
     * @method GET
     * @uri /tests/index
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        return view('test.index');
    }

    /**
     * @method GET
     * @uri /{id}
     * @param Request $request
     * @return void
     */
    public function show(Request $request, $id)
    {

    }

    /**
     * @method GET
     * @uri /tests/create
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {

    }

    /**
     * @method POST
     * @uri /tests/create
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {

    }

    /**
     * @method GET
     * @uri /tests/edit/{id}
     * @param Request $request
     * @return void
     */
    public function edit(Request $request, $id)
    {

    }

    /**
     * @method POST
     * @uri /tests/update/{id}
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * @method DELETE
     * @uri /{id}
     * @param Request $request
     * @return void
     */
    public function delete(Request $request, $id)
    {

    }
}
