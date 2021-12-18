<?php

namespace App\Events\Login;

use Illuminate\Auth\Events\Login;

class UserLoggedInEvent extends Login
{
    public function __construct($guard, $user, $remember)
    {
        parent::__construct($guard, $user, $remember);
    }
}
