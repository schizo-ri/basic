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
    $this->info("Izostanci!");
});

/* Artisan::command('command:clear_database', function () {
    $this->comment(clear_database::quote());
})->describe('Display an clear_database quote'); */

Artisan::command('notice', function () {
    $this->comment(notice::quote());
})->describe('Display an notice quote');

Artisan::command('email:campaign', function () {
    $this->comment(campaign::quote());
})->describe('Display an campaign quote');

Artisan::command('check_out', function () {
    $this->comment(CheckOut::quote());
})->describe('Display an workrecord quote');

Artisan::command('car_registration', function () {
    $this->info("Registracija vozila!");
});

Artisan::command('anniversary', function () {
    $this->info("Godišnjica!");
});

Artisan::command('medicalExamination', function () {
    $this->info("Liječnički pregled!");
});

Artisan::command('checkout_employee', function () {
    $this->info("Odjava radnika!");
});

Artisan::command('employeeTraining', function () {
    $this->info("Istek potvrde o osposobljavanju!");
});

Artisan::command('probation', function () {
    $this->info("Istek potvrde o osposobljavanju!");
});

Artisan::command('birthday', function () {
    $this->info("Rođendan djelatnika!");
});

Artisan::command('stranger', function () {
    $this->info("Istek dozvole za boravak!");
});

Artisan::command('task', function () {
    $this->info("Novi zadatak!");
});