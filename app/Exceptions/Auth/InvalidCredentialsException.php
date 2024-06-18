<?php

namespace App\Exceptions\Auth;

use App\Exceptions\AuthException;
use Symfony\Component\HttpFoundation\Response;

/**
 * InvalidCredentialsException.
 */
class InvalidCredentialsException extends AuthException
{
    protected $code = Response::HTTP_UNAUTHORIZED;
    protected $message = 'Invalid credentials';
}
