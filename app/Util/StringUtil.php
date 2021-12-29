<?php

namespace App\Util;

class StringUtil
{
    /**
     * @param int $length
     * @return false|string
     */
    public static function generateRandomString(int $length = 20)
    {
        return substr(md5(rand()), 0, $length);
    }
}
