<?php

namespace App\Exceptions\Auth;

use App\Exceptions\AuthException;
use Symfony\Component\HttpFoundation\Response;

/**
 * NeedToBeGuestException.
 */
class NeedToBeGuestException extends AuthException
{
    protected $code = Response::HTTP_UNAUTHORIZED;
    protected $message = 'Need to be guest (middleware)';
}
