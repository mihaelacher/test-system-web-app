<?php

namespace App\Util;

use Illuminate\Support\Facades\Session;

class MessageUtil
{
    /**
     * @param string $message
     * @return int
     */
    public static function success(string $message): int
    {
        Session::flash('message', $message);
        Session::flash('classes', 'alert-success');
        return 1;
    }

    /**
     * @param string $message
     * @return int
     */
    public static function error(string $message): int
    {
        Session::flash('message', $message);
        Session::flash('classes', 'alert-danger');
        return 0;
    }
}
