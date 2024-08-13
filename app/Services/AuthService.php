<?php

namespace App\Services;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\InvalidEmailVerificationException;
use App\Exceptions\Auth\TooManyAttemptsException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendResetPasswordLinkRequest;
use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tymon\JWTAuth\JWTGuard;

/**
 * Service for working with authentication module.
 */
class AuthService implements AuthServiceInterface
{
    public const AUTH_ROUTES_NAMES = [
        'register' => 'auth.register',
        'verify-email' => 'auth.verify-email',
        'login' => 'auth.login',
        'me' => 'auth.me',
        'logout' => 'auth.logout',
        'refresh' => 'auth.refresh',
        'send-reset-password-link' => 'auth.send-reset-password-link',
        'reset-password' => 'auth.reset-password',
    ];

    public const VERIFICATION_EMAIL_SUBJECT = 'Вершки и корешки - Завершение регистрации';
    public const RESET_PASSWORD_EMAIL_SUBJECT = 'Вершки и корешки - Сброс пароля';

    public const LOGIN_MAX_ATTEMPTS = 5;

    public const MIN_NAME_LENGTH = 2;
    public const MAX_NAME_LENGTH = 100;
    public const MAX_EMAIL_LENGTH = 100;
    public const MIN_PASSWORD_LENGTH = 6;

    /* Utilities */

    /**
     * @return string
     */
    public function getPasswordResetTokensTableName(): string
    {
        return config('auth.users_tables_prefix') . 'password_reset_tokens';
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getThrottleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    /* Methods */

    /**
     * AuthController > register().
     * New user registration.
     *
     * @param RegisterRequest $request
     * @return User
     */
    public function getNewUser(RegisterRequest $request): User
    {
        /** @var User $user */
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->sendEmailVerificationNotification();

        event(new Registered($user));

        return $user;
    }

    /**
     * AuthController > verifyEmail().
     * Check verification hash from email.
     *
     * @param Request $request
     * @return void
     * @throws AuthenticationException
     */
    public function checkVerificationHash(Request $request): void
    {
        if (!$request->hasValidSignature()) {
            $this->respondWithException(
                'Invalid verification link or signature',
                InvalidEmailVerificationException::class
            );
        }

        /* @var $auth JWTGuard|MustVerifyEmail */
        $auth = auth();

        event(new Verified($auth->user()));
    }

    /**
     * AuthController > login().
     * Attempt to authenticate the request's credentials.
     *
     * @param LoginRequest $loginRequest
     * @return bool|string
     * @throws AuthenticationException
     */
    public function authenticate(LoginRequest $loginRequest): bool|string
    {
        $throttleKey = $this->getThrottleKey($loginRequest);

        if (RateLimiter::tooManyAttempts($throttleKey, self::LOGIN_MAX_ATTEMPTS)) {
            event(new Lockout($loginRequest));

            $seconds = RateLimiter::availableIn($throttleKey);

            return $this->respondWithException(
                "Too many attempts (wait $seconds second(s))",
                TooManyAttemptsException::class
            );
        }

        /* @var $auth JWTGuard */
        $auth = auth();

        if (!$token = $auth->attempt($loginRequest->only('email', 'password'))) {
            RateLimiter::hit($throttleKey);

            return $this->respondWithException(
                exceptionClass: InvalidCredentialsException::class
            );
        }

        RateLimiter::clear($throttleKey);

        event(new Login('api', $auth->user(), true));

        return $token;
    }

    /**
     * AuthController > logout().
     * Attempt to users logout.
     *
     * @return void
     */
    public function logout(): void
    {
        /* @var $auth JWTGuard */
        $auth = auth();

        $auth->logout();

        event(new Logout('api', $auth->user()));
    }

    /**
     * AuthController > refresh().
     * Refresh user token.
     *
     * @return string
     */
    public function refreshToken(): string
    {
        /* @var $auth JWTGuard */
        $auth = auth();

        return $auth->refresh();
    }

    /**
     * AuthController > me().
     * Get the current user.
     *
     * @return Authenticatable
     */
    public function getCurrentUser(): Authenticatable
    {
        /* @var $auth JWTGuard */
        $auth = auth();

        return $auth->user();
    }

    /**
     * AuthController > sendResetPasswordLink().
     * Create and send password reset link.
     *
     * @param SendResetPasswordLinkRequest $request
     * @return string
     */
    public function sendResetPasswordLink(SendResetPasswordLinkRequest $request): string
    {
        /**
         * Errors (ValidationException):
         * 1. validation.email      // If not e-mail
         * 2. passwords.user        // PasswordBroker::INVALID_USER (If user not found by e-mail)
         * 3. passwords.throttled   // PasswordBroker::RESET_THROTTLED (Token already exists?)
         */

        return Password::sendResetLink(
            $request->only('email')
        );
    }

    /**
     * AuthController > resetPassword().
     * Reset users password.
     *
     * @param ResetPasswordRequest $request
     * @return string
     */
    public function resetPassword(ResetPasswordRequest $request): string
    {
        /**
         * Errors (ValidationException):
         * 1. validation.email  // If not e-mail
         * 2. passwords.user    // PasswordBroker::INVALID_USER (If user not found by e-mail)
         * 3. passwords.token   // PasswordBroker::INVALID_TOKEN (Token is invalid)
         */

        return Password::reset(
            $request->only('email', 'password', 'token'),
            static function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }

    /* Responses */

    /**
     * Standard response.
     *
     * @param string $message
     * @param User|null $user
     * @param int|null $status
     * @return JsonResponse
     */
    public function respond(string $message, ?User $user = null, ?int $status = null): JsonResponse
    {
        $data = [
            'message' => $message,
        ];

        if ($user) {
            $data['user'] = $user;
        }

        return ($status)
            ? response()->json($data, $status)
            : response()->json($data)
        ;
    }

    /**
     * Response with token.
     *
     * @param string $token
     * @return JsonResponse
     */
    public function respondWithToken(string $token): JsonResponse
    {
        /* @var $auth JWTGuard */
        $auth = auth();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $auth->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Response with exception.
     *
     * @param string $message
     * @param string|null $exceptionClass
     * @return null
     * @throws AuthenticationException
     */
    public function respondWithException(string $message = 'Authentication exception', ?string $exceptionClass = null): null
    {
        if (!$exceptionClass || !class_exists($exceptionClass)) {
            $exceptionClass = AuthenticationException::class;
        }

        throw new $exceptionClass($message);
    }
}
