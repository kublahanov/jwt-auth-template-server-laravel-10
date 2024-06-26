<?php

namespace App\Services;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\InvalidEmailVerificationException;
use App\Exceptions\Auth\TooManyAttemptsException;
use App\Http\Requests\Auth\LoginRequest;
use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
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
    ];

    public const VERIFICATION_URL_LIFE_TIME_IN_MINUTES = 60;

    public const VERIFICATION_EMAIL_SUBJECT = 'Вершки и корешки - Завершение регистрации';

    public const LOGIN_MAX_ATTEMPTS = 5;

    /**
     * @param Request $request
     * @return string
     */
    public function getThrottleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    /**
     * @param User $user
     * @return string
     */
    public function getVerificationUrl(User $user): string
    {
        return URL::temporarySignedRoute(
            self::AUTH_ROUTES_NAMES['verify-email'],
            now()->addMinutes(self::VERIFICATION_URL_LIFE_TIME_IN_MINUTES),
            [
                'id' => $user->id,
                'hash' => urlencode(Hash::make($user->email)),
            ]
        );
    }

    /**
     * @param string $userName
     * @param string $userEmail
     * @param string $userPassword
     * @return User
     */
    public function getNewUser(string $userName, string $userEmail, string $userPassword): User
    {
        /** @var User $user */
        $user = User::create([
            'name' => $userName,
            'email' => $userEmail,
            'password' => Hash::make($userPassword),
        ]);

        $user->notify(
            new VerifyEmail(
                $this->getVerificationUrl($user)
            )
        );

        return $user;
    }

    /**
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

        return $token;
    }

    /**
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
    }

    /**
     * Get the token array structure.
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
     * Standard response.
     *
     * @param string $message
     * @param User|null $user
     * @param int|null $status
     * @return JsonResponse
     */
    public function respond(string $message, User $user = null, int $status = null): JsonResponse
    {
        $data = [
            'message' => $message,
        ];

        if ($user) {
            $data['user'] = $user;
        }

        return ($status)
            ? response()->json($data, $status)
            : response()->json($data);
    }

    /**
     * Response with exception.
     *
     * @param string $message
     * @param string|null $exceptionClass
     * @return null
     * @throws AuthenticationException
     */
    public function respondWithException(string $message = 'Authentication exception', string $exceptionClass = null): null
    {
        if (!$exceptionClass || !class_exists($exceptionClass)) {
            $exceptionClass = AuthenticationException::class;
        }

        throw new $exceptionClass($message);
    }
}
