<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;
use App\Mail\GreetingCardMail;
use App\Mail\BirthDayMail;
use App\Http\Controllers\EmailingController;
use DateTime;
use Log;

class BirthdayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rođendan djelatnika';

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
        Log::info('BirthdayCommand');

        $send_to = EmailingController::sendTo('employees','cron');
      /*   $send_to = array('uprava@duplico.hr'); */ 
        array_push($send_to , 'jelena.juras@duplico.hr');

        $datum = new DateTime('now');
        $employees = Employee::employeesBday($datum);
		
		foreach($employees as $employee) {
            foreach($send_to as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new BirthDayMail( $employee )); 
                }
            }

            $employee_mail = $employee->email;
            Mail::to($employee_mail)->send(new GreetingCardMail( $employee )); 
        }
    }
}
