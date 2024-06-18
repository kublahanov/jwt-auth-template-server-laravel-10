<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\Auth\NeedToBeAuthenticatedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * NeedToBeAuthenticated.
 */
class NeedToBeAuthenticated
{
    public function handle(Request $request, \Closure $next, $guard)
    {
        if (Auth::guard($guard)->guest()) {
            throw new NeedToBeAuthenticatedException();
        }

        return $next($request);
    }
}
