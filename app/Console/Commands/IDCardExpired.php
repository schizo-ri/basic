<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;
use App\Mail\IDCardExpiredMail;
use App\Http\Controllers\EmailingController;
use DateTime;
use Log;

class IDCardExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'idCard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Istek osobne iskaznice';

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

        // Istek osobne iskaznice za 30 dana

        $datum = new DateTime('now');
		$datum->modify('+1 month');
        $employees = Employee::employeesIDCardExpired($datum);
        
        foreach($employees as $employee) {
            foreach(array_unique($send_to) as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new IDCardExpiredMail($employee)); 
                }
            }
            Mail::to($employee->email)->send(new IDCardExpiredMailEmpl($employee)); 
        }

        $this->info('Obavijest je poslana!');
    }
}
