<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Mail\StrangerMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\EmailingController;
use DateTime;
use Log;

class StrangerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stranger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Istek dozvole za boravak';

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

        $datum = new DateTime('now');
        $datum->modify('+75 days');
        
        $employees = Employee::employeeStranger($datum);
        foreach ($employees as $employee) {
            foreach(array_unique( $send_to ) as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new StrangerMail($employee)); 
                }
            }
        }
    }
}
