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

// Route::get('/', function () {
//     return view('welcome');
// });
//
// Route::get('/login', fn() => print 'Login')->name('login');
// Route::get('/register', fn() => print 'Register')->name('register');
//
// /**
//  * Examples and tests.
//  */
// Route::get('/example', fn() => view('example'))->name('example');
// Route::get('/mailable', function () {
//     return new App\Mail\TestMail();
// });

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';
