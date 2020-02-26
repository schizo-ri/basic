<?php

use Illuminate\Foundation\Inspiring;

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
})->describe('Display an inspiring quote');

Artisan::command('email:absence_day', function () {
    $this->comment(absence_day::quote());
})->describe('Display an Absences quote');

Artisan::command('command:clear_database', function () {
    $this->comment(clear_database::quote());
})->describe('Display an clear_database quote');

Artisan::command('notice', function () {
    $this->comment(notice::quote());
})->describe('Display an notice quote');