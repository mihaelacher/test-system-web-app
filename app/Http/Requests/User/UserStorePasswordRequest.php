<?php

namespace App\Http\Requests\User;

use App\Http\Requests\MainFormRequest;
use App\Models\Authorization\User;
use Illuminate\Support\Facades\Validator;

class UserStorePasswordRequest extends MainFormRequest
{
    public function authorize(): bool
    {
        return $this->currentUser->can('update', User::findOrFail(request()->id));
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required|one_lowercase|one_uppercase|one_digit|password_format|confirmed'
        ];
    }

    public function extendValidatorRules()
    {
        Validator::extend('one_lowercase', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^.*[a-z]+.*/u', $value);
        }, 'Password must contain at least one lower case letter!');

        Validator::extend('one_uppercase', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^.*[A-Z]+.*/u', $value);
        }, 'Password must contain at least one upper case letter!');

        Validator::extend('one_digit', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^.*\d+.*/u', $value);
        }, 'Password must contain at least one number!');

        Validator::extend('password_format', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[\w\d!@_*\.-]+$/', $value);
        }, 'Password contains invalid characters! Allowed are: "!@.-_*');
    }
}
