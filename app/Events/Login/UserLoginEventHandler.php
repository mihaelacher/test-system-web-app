<?php

namespace App\Events\Login;

use Illuminate\Contracts\Queue\ShouldQueue;

class UserLoginEventHandler implements ShouldQueue
{
    public function handle(UserLoggedInEvent $event)
    {
        if (is_null($event->user)) {
            return;
        }


    }
}
