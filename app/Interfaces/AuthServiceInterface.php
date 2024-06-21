<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function getThrottleKey(Request $request): string;

    public function getVerificationUrl(int $userId, string $userEmail): string;

    public function getNewUser(string $userName, string $userEmail, string $userPassword): User;

    public function checkVerificationHash(Request $request): void;

    public function respondWithToken(string $token): JsonResponse;

    public function respond(string $message, User $user = null, int $status = null): JsonResponse;

    public function respondWithException(string $message = 'Authentication exception', string $exceptionClass = null);
}
