<?php

namespace App\Exceptions\Auth;

use App\Exceptions\AuthException;
use Symfony\Component\HttpFoundation\Response;

/**
 * InvalidEmailVerificationException.
 */
class InvalidEmailVerificationException extends AuthException
{
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;
    protected $message = 'Invalid verification link or signature';
}
