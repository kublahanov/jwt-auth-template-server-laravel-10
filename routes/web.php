<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Route as RouteAlias;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.welcome');
});

/**
 * Output routes list in HTML or JSON.
 */
Route::get('/api', function (Request $request) {
    $routes = collect(Route::getRoutes())
        ->filter(function (RouteAlias $route) {
            return str_starts_with($route->uri(), 'api');
        })
        ->filter(function (RouteAlias $route) {
            return !empty($route->getName());
        })
        ->map(function (RouteAlias $route) use ($request) {
            $result = [
                'uri' => $route->uri(),
                'methods' => implode(', ', $route->methods()),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => implode(', ', $route->gatherMiddleware()),
            ];

            if (!$request->has('json')) {
                $result['link'] = in_array('GET', $route->methods())
                    ? url($route->uri())
                    : ''
                ;
            }

            return $result;
        })
        ->keyBy('name')
        ->sortKeys()
    ;

    return ($request->isJson())
        ? response()->json($routes)
        : response()->view('pages.api-list', ['routes' => $routes])
    ;
});

/**
 * TODO: Examples and tests.
 */
Route::get('/example', fn() => view('examples.example-domain'))->name('example');
Route::get('/mailable', function () {
    return new App\Mail\TestMail();
});
