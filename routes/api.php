<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TestController;
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
 * Вывод списка роутов АПИ в формате JSON.
 */
Route::get('/', function () {
    $routes = collect(Route::getRoutes())
        ->filter(function ($route) {
            return str_starts_with($route->uri(), 'api');
        })
        ->map(function ($route) {
            return [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->gatherMiddleware(),
            ];
        });

    return response()->json($routes, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/migrations', [TestController::class, 'migrations']);

Route::prefix('auth')->group(function ($router) {
    Route::get('me', [AuthController::class, 'me']);

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
