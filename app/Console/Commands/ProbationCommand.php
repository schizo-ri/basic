<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Mail\ProbationMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\EmailingController;
use DateTime;

class ProbationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'probation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probni rok';

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
        $send_to = EmailingController::sendTo('employees','cron');

        // Probni rok za 7 dana
        $datum = new DateTime('now');
        $datum->modify('-6 month');
        $datum->modify('+7 days');
        
        $employees = Employee::employeesProbation($datum);

		foreach($employees as $employee) {
            foreach($send_to as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new ProbationMail($employee)); 
                }
            }
        }

        // Probni rok za 15 dana
        $datum = new DateTime('now');
        $datum->modify('-6 month');
        $datum->modify('+15 days');
        
        $employees = Employee::employeesProbation($datum);

        foreach($employees as $employee) {
            foreach($send_to as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new ProbationMail($employee)); 
                }
            }
        }

        // Probni rok za 30 dana
        $datum = new DateTime('now');
        $datum->modify('-6 month');
        $datum->modify('+1 month');
        
        $employees = Employee::employeesProbation($datum);

        foreach($employees as $employee) {
            foreach($send_to as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new ProbationMail($employee)); 
                }
            }
        }
    }
}