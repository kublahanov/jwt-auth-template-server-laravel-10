<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthService implements AuthServiceInterface
{
    public function getThrottleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }
}
