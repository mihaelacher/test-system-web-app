<?php

namespace App\Http\Requests;

use App\Models\Authorization\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class MainGetRequest extends Request
{
    /**
     * @var User $currentUser
     */
    public $currentUser;

    public function __construct()
    {
        parent::__construct();
        $this->currentUser = Auth::user();
        if (!$this->authorize()) {
            $this->failedAuthorization();
        }
        foreach (request()->input() as $key => $getParameter) {
            $this->$key = $getParameter;
        }
    }

    public abstract function authorize();

    public function failedAuthorization()
    {
        abort(403);
    }
}
