<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Вывод таблицы с пользовательскими командами
 * (наследуются от класса App\Console\Commands).
 */
Artisan::command('x', function () {
    $list = collect(Artisan::all())
        ->filter(function ($value, $key) {
            return str_starts_with($key, 'x-');
        })
        ->transform(function ($value, $key) {
            return [
                "command" => $key,
                "description" => $value->getDescription(),
            ];
        })
        ->toArray()
    ;

    $this->newLine();
    $this->line('List of user-defined commands:');

    $this->table(
        ['Command', 'Description'],
        $list,
        'box',
    );
})->purpose('Display only user-defined commands');
