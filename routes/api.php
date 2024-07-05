<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TestController;
use App\Services\AuthService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * TODO: Test.
 */
Route::get('/test/migrations', [TestController::class, 'migrations'])->name('test.migrations');

/**
 * AuthController.
 */
Route::prefix('auth')->group(function ($router) {
    Route::post('register', [AuthController::class, 'register'])
        ->name(AuthService::AUTH_ROUTES_NAMES['register']);
    Route::get('verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'decode.hash'])
        ->name(AuthService::AUTH_ROUTES_NAMES['verify-email']);

    Route::post('login', [AuthController::class, 'login'])
        ->name(AuthService::AUTH_ROUTES_NAMES['login']);
    Route::post('logout', [AuthController::class, 'logout'])
        ->name(AuthService::AUTH_ROUTES_NAMES['logout']);
    Route::post('refresh', [AuthController::class, 'refresh'])
        ->name(AuthService::AUTH_ROUTES_NAMES['refresh']);

    Route::get('me', [AuthController::class, 'me'])
        ->name(AuthService::AUTH_ROUTES_NAMES['me']);

    Route::post('send-reset-password-link', [AuthController::class, 'sendResetPasswordLink'])
        ->name(AuthService::AUTH_ROUTES_NAMES['send-reset-password-link']);
    Route::post('reset-password', [AuthController::class, 'resetPassword'])
        ->name(AuthService::AUTH_ROUTES_NAMES['reset-password']);
});
