<?php

namespace App\Exceptions\Auth;

use App\Exceptions\AuthException;
use Symfony\Component\HttpFoundation\Response;

/**
 * NeedToBeAuthenticatedException.
 */
class NeedToBeAuthenticatedException extends AuthException
{
    protected $code = Response::HTTP_UNAUTHORIZED;
    protected $message = 'Need to be authenticated (middleware)';
}
