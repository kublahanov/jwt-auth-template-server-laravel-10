<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;

/**
 * DecodeHashParameter.
 */
class DecodeHashParameter
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('hash')) {
            $decodedHash = urldecode($request->input('hash'));
            $request->merge(['hash' => $decodedHash]);
        }

        return $next($request);
    }
}
