<?php

namespace App\Http\Requests;

trait AuthorizeAdminRequestTrait
{
    public function authorize()
    {
        return $this->currentUser->is_admin;
    }
}
