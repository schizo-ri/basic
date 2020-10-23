<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeTermination;
use Illuminate\Support\Facades\Mail;
use App\Mail\TermintionMail;
use App\Http\Controllers\EmailingController;

class TerminationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkout_employee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Odjava radnika';

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
        $employees = EmployeeTermination::EmployeeTerminationToday();
        $send_to = EmailingController::sendTo('employee_terminations','create');

        foreach ( $employees as $employee ) {
            foreach($send_to as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new TermintionMail($employee)); 
                }
            }
        }
    }
}
