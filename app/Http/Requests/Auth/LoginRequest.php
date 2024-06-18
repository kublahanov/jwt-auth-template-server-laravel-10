<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\TooManyAttemptsException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tymon\JWTAuth\JWTGuard;

/**
 * LoginRequest.
 */
class LoginRequest extends FormRequest
{
    public $result;

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
     * @return bool|string
     * @throws InvalidCredentialsException
     * @throws TooManyAttemptsException
     */
    public function authenticate()
    {
        $throttleKey = Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            event(new Lockout($this));

            $seconds = RateLimiter::availableIn($throttleKey);

            throw new TooManyAttemptsException("Too many attempts (wait $seconds second(s))");
        }

        /* @var $auth JWTGuard */
        $auth = auth();

        if (!$token = $auth->attempt($this->only('email', 'password'), true)) {
            RateLimiter::hit($throttleKey);

            throw new InvalidCredentialsException();
        }

        RateLimiter::clear($throttleKey);

        return $token;
    }
}
