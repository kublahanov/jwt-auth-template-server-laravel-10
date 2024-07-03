<?php

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
    // return ['Laravel' => app()->version()];
    return view('welcome');
});

/**
 * Examples and tests.
 */
Route::get('/example', fn() => view('examples.example-domain'))->name('example');
Route::get('/mailable', function () {
    return new App\Mail\TestMail();
});

// require __DIR__ . '/auth.php';
