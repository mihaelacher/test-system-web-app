<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\AuthorizeAdminRequestTrait;
use App\Http\Requests\MainFormRequest;

class StoreTestInvitationsRequest extends MainFormRequest
{
    use AuthorizeAdminRequestTrait;

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'active_from' => 'required|date_format:d.m.Y H:i|before:active_to',
            'active_to' => 'required|date_format:d.m.Y H:i|after:active_from'
        ];
    }
}
