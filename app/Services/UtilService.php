<?php

namespace App\Services;

use Carbon\Carbon;

class UtilService
{
    /**
     * @param string $stringDate
     * @return string
     */
    public static function formatDate(string $stringDate): string
    {
        return Carbon::parse($stringDate)->format('d.m.Y H:i:s');
    }
}
