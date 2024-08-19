<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Route as RouteAlias;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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
        ->filter(fn (RouteAlias $route) => str_starts_with($route->uri(), 'api'))
        ->filter(fn (RouteAlias $route) => !empty($route->getName()))
        ->map(function (RouteAlias $route) use ($request) {
            $result = [
                'uri' => $route->uri(),
                'methods' => implode(', ', $route->methods()),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => implode(', ', $route->gatherMiddleware()),
            ];

            if (!$request->has('json') && !$request->isJson()) {
                $result['link'] = in_array('GET', $route->methods())
                    ? url($route->uri())
                    : '';
            }

            return $result;
        })
        ->keyBy('name')
        ->sortKeys();

    if ($request->has('only')) {
        $onlyString = $request->get('only');
        $onlyArray = Str::contains($onlyString, ',')
            ? explode(',', $onlyString)
            : [$onlyString];

        $routes = $routes->filter(function (array $route) use ($onlyArray) {
            foreach ($onlyArray as $item) {
                if (str_starts_with($route['uri'], 'api/' . $item)) {
                    return true;
                }
            }

            return false;
        });
    }

    return ($request->isJson())
        ? response()->json($routes)
        : response()->view('pages.api-list', ['routes' => $routes]);
})->name('api-list');

/**
 * TODO: Examples and tests.
 */
Route::get('/example', fn () => view('examples.example-domain'))->name('example');
Route::get('/mailable', function () {
    return new App\Mail\TestMail();
});
