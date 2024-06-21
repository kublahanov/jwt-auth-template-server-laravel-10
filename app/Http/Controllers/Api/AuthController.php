<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Auth\InvalidEmailVerificationException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\JWTGuard;

/**
 * AuthController.
 */
class AuthController extends ApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(protected AuthServiceInterface $authService)
    {
        $this->middleware('auth:api', ['except' => [
            'login',
            'register',
        ]]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->respondWithToken(
            $this->authService->authenticate($request)
        );
    }

    /**
     * Register a User.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->getNewUser($request->name, $request->email, $request->password);

        return $this->authService->respond(
            'User registered successfully, please check your email for verification link',
            $user,
            Response::HTTP_CREATED
        );
    }

    /**
     * Email verification.
     *
     * @param Request $request
     * @param $id
     * @param $hash
     * @return JsonResponse
     */
    public function verifyEmail(Request $request, $id, $hash): JsonResponse
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            return $this->authService->respondWithException(
                'Invalid verification link or signature',
                InvalidEmailVerificationException::class
            );
        }

        if ($user->hasVerifiedEmail()) {
            return $this->authService->respond('Email already verified');
        }

        $user->markEmailAsVerified();

        return $this->authService->respond('Email verified successfully');
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        /* @var $auth JWTGuard */
        $auth = auth();

        return $this->authService->respond(
            'Current user fetched successfully',
            $auth->user()
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        /* @var $auth JWTGuard */
        $auth = auth();

        $auth->logout();

        return $this->authService->respond(
            'Successfully logged out',
            status: Response::HTTP_ACCEPTED
        );
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        /* @var $auth JWTGuard */
        $auth = auth();

        return $this->authService->respondWithToken(
            $auth->refresh()
        );
    }
}
