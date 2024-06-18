<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function getThrottleKey(Request $request);
}
