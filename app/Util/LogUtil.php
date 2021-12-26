<?php

namespace App\Util;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Util class for reporting exception
 * Should be extended if used in production
 */
class LogUtil
{
    /**
     * @param string $message
     * @return void
     */
    public static function warn(string $message): void
    {
        Log::warning($message);
    }

    /**
     * @param string $message
     * @return void
     */
    public static function error(string $message): void
    {
        Log::error($message);
    }

    /**
     * @param string $message
     * @param \Exception|null $exception
     * @return void
     */
    public static function logError(string $message, \Exception $exception = null): void
    {
        // Convert message to exception if needed
        if (is_null($exception)) {
            $exception = new \Exception($message);
        }

        self::reportException($exception);
    }

    private static function reportException(\Exception $exception)
    {
        try {
            self::getUserId();
        } catch (\Exception $e) {
            $exception = $e;
        }

        self::error($exception->getMessage(), false);
    }

    /**
     * @return int
     */
    private static function getUserId(): int
    {
        $loggedUser = Auth::user();
        // Not logged user
        if ($loggedUser == null) {
            return 'Not logged in';
        }

        return $loggedUser->id;
    }
}
