<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\Auth\NeedToBeGuestException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * NeedToBeGuest.
 */
class NeedToBeGuest
{
    /**
     * @throws NeedToBeGuestException
     */
    public function handle(Request $request, Closure $next, $guard)
    {
        if (Auth::guard($guard)->check()) {
            throw new NeedToBeGuestException;
        }

        return $next($request);
    }
}
