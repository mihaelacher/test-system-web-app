<?php

namespace App\Http\Requests;

use App\Models\Authorization\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

abstract class MainFormRequest extends FormRequest
{
    /**
     * @var User $currentUser
     */
    public $currentUser;

    public function __construct()
    {
        parent::__construct();
        $this->currentUser = Auth::user();
        $this->extendValidatorRules();
    }

    // remove javascript -- tags XSS defense
    protected function sanitizeInput()
    {
        // TODO
    }

    public function rules()
    {
        return [];
    }

    public function extendValidatorRules()
    {

    }
}
