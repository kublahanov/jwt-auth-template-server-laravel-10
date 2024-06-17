<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use stdClass;

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
     * @return stdClass
     */
    public function authenticate()
    {
        $result = new stdClass();

        $throttleKey = Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            event(new Lockout($this));

            $seconds = RateLimiter::availableIn($throttleKey);

            $result->error = "Too many attempts (wait $seconds seconds)";
            $result->status = 429;

            return $result;
        }

        if (!$result->token = auth()->attempt($this->only('email', 'password'), true)) {
            RateLimiter::hit($throttleKey);

            $result->error = "Invalid credentials";
            $result->status = 401;

            return $result;
        }

        $result->error = false;

        RateLimiter::clear($throttleKey);

        return $result;
    }
}
