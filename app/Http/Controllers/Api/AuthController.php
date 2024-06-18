<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\TooManyAttemptsException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws InvalidCredentialsException
     * @throws TooManyAttemptsException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->respondWithToken($request->authenticate($this->authService));
    }

    /**
     * Register a User.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|between:2,100',
        //     'email' => 'required|string|email|max:100|unique:' . (new User)->getTable(),
        //     'password' => 'required|string|confirmed|min:6',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 400);
        // }

        $user = User::create(array_merge(
            $request->only(['name', 'email']),
            ['password' => bcrypt($request->get('password'))]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], Response::HTTP_CREATED);
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

        return response()->json($auth->user());
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

        return response()->json([
            'message' => 'Successfully logged out',
        ], Response::HTTP_ACCEPTED);
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

        return $this->respondWithToken($auth->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        /* @var $auth JWTGuard */
        $auth = auth();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $auth->factory()->getTTL() * 60,
        ]);
    }
}
