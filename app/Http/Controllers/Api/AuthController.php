<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendResetPasswordLinkRequest;
use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
            'verifyEmail',
            'sendResetPasswordLink',
            'resetPassword',
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }
        //
        // $reset = DB::table('password_resets')->where('email', $request->email)->first();
        //
        // if (!$reset || !Hash::check($request->token, $reset->token)) {
        //     return response()->json(['message' => 'Invalid token'], 400);
        // }
        //
        // $user = User::where('email', $request->email)->first();
        //
        // if (!$user) {
        //     return response()->json(['message' => 'User not found'], 404);
        // }
        //
        // $user->password = Hash::make($request->password);
        // $user->save();
        //
        // // Delete the reset token
        // DB::table('password_resets')->where('email', $request->email)->delete();
        //
        // return response()->json(['message' => 'Password reset successful']);

        dd('OKAY');

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    // 'remember_token' => Str::random(60), // TODO: Зачем это?
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }
}
