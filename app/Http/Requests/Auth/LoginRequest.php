<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\TooManyAttemptsException;
use App\Interfaces\AuthServiceInterface;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Tymon\JWTAuth\JWTGuard;

/**
 * LoginRequest.
 */
class LoginRequest extends FormRequest
{
    protected int $maxAttempts = 5;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @param AuthServiceInterface $authService
     * @return bool|string
     * @throws InvalidCredentialsException
     * @throws TooManyAttemptsException
     */
    public function authenticate(AuthServiceInterface $authService): bool|string
    {
        $throttleKey = $authService->getThrottleKey($this);

        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            event(new Lockout($this));

            $seconds = RateLimiter::availableIn($throttleKey);

            throw new TooManyAttemptsException("Too many attempts (wait $seconds second(s))");
        }

        /* @var $auth JWTGuard */
        $auth = auth();

        if (!$token = $auth->attempt($this->only('email', 'password'))) {
            RateLimiter::hit($throttleKey);

            throw new InvalidCredentialsException();
        }

        RateLimiter::clear($throttleKey);

        return $token;
    }
}
