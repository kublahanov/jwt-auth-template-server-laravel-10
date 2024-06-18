<?php

namespace App\Exceptions\Auth;

use App\Exceptions\AuthException;
use Symfony\Component\HttpFoundation\Response;

/**
 * TooManyAttemptsException.
 */
class TooManyAttemptsException extends AuthException
{
    protected $code = Response::HTTP_TOO_MANY_REQUESTS;
    protected $message = 'Too many attempts';
}
