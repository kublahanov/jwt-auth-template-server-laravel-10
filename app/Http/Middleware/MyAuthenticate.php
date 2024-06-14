<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * MyAuthenticate.
 */
class MyAuthenticate
{
    public function handle(Request $request, \Closure $next, $guard)
    {
        if (Auth::guard($guard)->guest()) {
            return response()->json(['message' => 'Unauthorized 1'], 401);
        }

        return $next($request);
    }
}
