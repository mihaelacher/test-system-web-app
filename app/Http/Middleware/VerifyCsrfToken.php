<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
       if ($request->ajax()) {
           try {
               return parent::handle($request, $next);
           } catch (TokenMismatchException $e) {
               abort(401, 'The CSRF Tokens do not match.');
           }
       } else {
           return parent::handle($request, $next);
       }
    }
}
