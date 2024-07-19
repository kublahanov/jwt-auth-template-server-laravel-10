<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendResetPasswordLinkRequest;
use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

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
            'register',
            'verifyEmail',
            'login',
            'sendResetPasswordLink',
            'resetPassword',
        ]]);
    }

    /**
     * Register a User.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->getNewUser($request);

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
     * @return JsonResponse
     */
    public function verifyEmail(Request $request, $id): JsonResponse
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        $this->authService->checkVerificationHash($request);

        if ($user->hasVerifiedEmail()) {
            return $this->authService->respond('Email already verified');
        }

        $user->markEmailAsVerified();

        return $this->authService->respond('Email verified successfully');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->respondWithToken(
            $this->authService->authenticate($request)
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

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
        $token = $this->authService->refreshToken();

        return $this->authService->respondWithToken(
            $token
        );
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        /* @var $user User */
        $user = $this->authService->getCurrentUser();

        return $this->authService->respond(
            'Current user fetched successfully',
            $user
        );
    }

    /**
     * Send a reset password link to the given user.
     *
     * @param SendResetPasswordLinkRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function sendResetPasswordLink(SendResetPasswordLinkRequest $request): JsonResponse
    {
        $status = $this->authService->sendResetPasswordLink($request);

        /**
         * TODO: Возможно стоит заменить собственными Exceptions.
         */
        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => $status,
            ]);
        }

        return $this->authService->respond(
            'Password reset link successfully sent',
            status: Response::HTTP_ACCEPTED
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->resetPassword($request);

        /**
         * TODO: Возможно стоит заменить собственными Exceptions.
         */
        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => $status,
            ]);
        }

        return $this->authService->respond(
            'Password reset successfully',
            status: Response::HTTP_ACCEPTED
        );
    }
}
