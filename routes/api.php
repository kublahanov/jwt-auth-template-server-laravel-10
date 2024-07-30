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
Route::prefix('auth')->controller(AuthController::class)->group(function ($router) {
    Route::post('register', 'register')
        ->name(AuthService::AUTH_ROUTES_NAMES['register']);
    Route::get('verify-email/{id}/{hash}', 'verifyEmail')
        ->middleware(['signed', 'decode.hash'])
        ->name(AuthService::AUTH_ROUTES_NAMES['verify-email']);

    Route::post('login', 'login')
        ->name(AuthService::AUTH_ROUTES_NAMES['login']);
    Route::post('logout', 'logout')
        ->name(AuthService::AUTH_ROUTES_NAMES['logout']);
    Route::post('refresh', 'refresh')
        ->name(AuthService::AUTH_ROUTES_NAMES['refresh']);

    Route::get('me', 'me')
        ->name(AuthService::AUTH_ROUTES_NAMES['me']);

    Route::post('send-reset-password-link', 'sendResetPasswordLink')
        ->name(AuthService::AUTH_ROUTES_NAMES['send-reset-password-link']);
    Route::post('reset-password', 'resetPassword')
        ->name(AuthService::AUTH_ROUTES_NAMES['reset-password']);
});
