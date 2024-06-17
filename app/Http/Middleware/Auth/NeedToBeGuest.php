<?php

namespace App\Http\Middleware\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * NeedToBeGuest.
 */
class NeedToBeGuest
{
    public function handle(Request $request, \Closure $next, $guard)
    {
        if (Auth::guard($guard)->check()) {
            return response()->json(['error' => 'Need to be guest (middleware)'], 401);
        }

        return $next($request);
    }
}
