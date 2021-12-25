<?php

namespace App\Http\Requests\User;

trait ValidateUserRequestTrait
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email'
        ];
    }
}
