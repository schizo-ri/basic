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

Artisan::command('command:listUpdate', function () {
    $this->comment(listUpdate::quote());
})->describe('Display an listUpdate quote');
Artisan::command('command:preparation_create', function () {
    $this->comment(PreparationCreate::quote());
})->describe('Display an preparation_create quote');
Artisan::command('command:preparation_update', function () {
    $this->comment(PreparationUpdate::quote());
})->describe('Display an PreparationUpdate quote');
Artisan::command('schedule', function () {
    $this->comment(DesigningScheduleCommand::quote());
})->describe('Display an DesigningScheduleCommand quote');