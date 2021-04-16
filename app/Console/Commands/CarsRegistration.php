<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\CarsRegistrationMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\EmailingController;
use App\Models\Car;
use DateTime;
use Log;

class CarsRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'car_registration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registracija vozila';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $send_to = EmailingController::sendTo('cars','cron');
        array_push( $send_to, 'jelena.juras@duplico.hr');
        

        $today = new DateTime();
        $today->modify('-1 years');
        $today->modify('+14 days');

        $cars = Car::where('last_registration', $today->format('Y-m-d') )->get();

        if( count($cars) > 0) {
            foreach($send_to as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    foreach($cars as $car) {
                        Mail::to(trim($send_to_mail))->send(new CarsRegistrationMail( $car )); // mailovi upisani u mailing 
                    }
                }
            }
        }
        return "Registracija vozila";
    }
}
