<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeTraining;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeTrainingMail;
use App\Http\Controllers\EmailingController;
use DateTime;
use Log;

class EmployeeTrainingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employeeTraining';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Istek potvrde o osposobljavanju';

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
        $send_to = EmailingController::sendTo('employee_trainings','cron');

        $datum = new DateTime('now');
        $datum->modify('+2 month');
        Log::info('EmployeeTrainingCommand ' . ' | ' .  date_format($datum,'Y-m-d'));

        $employeeTrainings = EmployeeTraining::EmployeeTrainingDate($datum);
		foreach($employeeTrainings as $employeeTraining) {
            if(  $employeeTraining->employee->checkout == null ) {
                foreach(arryy_unique($send_to) as $send_to_mail) {
                    if( $send_to_mail != null & $send_to_mail != '' ) {
                        Mail::to($send_to_mail)->send(new EmployeeTrainingMail($employeeTraining)); 
                    }
                }
            }
        }
    }
}
