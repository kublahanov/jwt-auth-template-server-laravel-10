<?php

namespace App\Interfaces;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendResetPasswordLinkRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface AuthServiceInterface
{
    /* Utilities */

    public function getPasswordResetTokensTableName(): string;

    public function getThrottleKey(Request $request): string;

    /* Methods */

    public function getNewUser(RegisterRequest $request): User;

    public function checkVerificationHash(Request $request): void;

    public function authenticate(LoginRequest $loginRequest): bool|string;

    public function logout(): void;

    public function refreshToken(): string;

    public function getCurrentUser(): Authenticatable;

    public function sendResetPasswordLink(SendResetPasswordLinkRequest $request): string;

    public function resetPassword(ResetPasswordRequest $request): string;

    /* Responses */

    public function respond(string $message, ?User $user = null, ?int $status = null): JsonResponse;

    public function respondWithToken(string $token): JsonResponse;

    public function respondWithException(string $message = 'Authentication exception', ?string $exceptionClass = null);
}
