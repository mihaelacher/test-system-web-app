<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('showLoginForm');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if (!$this->auth->guard($guard)->guest()) {
                return $next($request);
            }
        }

        if ($request->ajax()) {
            return response('Unauthorized', 401);
        } else {
            return redirect()->guest('/auth/login');
        }
    }
}
