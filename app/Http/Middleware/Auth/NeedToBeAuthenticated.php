<?php

namespace App\Http\Middleware\Auth;

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
            return response()->json(['error' => 'Need to be authenticated (middleware)'], 401);
        }

        return $next($request);
    }
}
