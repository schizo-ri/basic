<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnniversaryMail;
use DateTIme;
use App\Http\Controllers\EmailingController;
use Log;

class AnniversaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anniversary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Djelatnik ima godišnjicu rada u firmi';

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
        array_push($send_to , 'jelena.juras@duplico.hr');
        
        // godišnjica na današnji dan
        $datum = new DateTime('now');
       
        $employees = Employee::employeesAnniversary( $datum );
        if(count($employees) > 0) {
            foreach ($employees as $employee) {
                $date1 = new DateTime($employee->reg_date); 
                $interval = $date1->diff($datum); 
                $years = $interval->format('%y'); 
                
                if($years > 0) {
                    foreach($send_to as $send_to_mail) {
                        if( $send_to_mail != null & $send_to_mail != '' ) {
                            Mail::to($send_to_mail)->send(new AnniversaryMail( $employee )); 
                        }
                    }
                }
            }
        }

        // godišnjica za 5 dana
        $datum2 = new DateTime('now');
		$datum2->modify('+5 days');
		
        $employees = Employee::employeesAnniversary( $datum2 );
        if(count($employees) > 0) {
            foreach ($employees as $employee) {
                $date1 = new DateTime($employee->reg_date); 
                $interval = $date1->diff($datum2); 
                $years = $datum2->format('Y') - $date1->format('Y') ; 
                
                if($years > 0) {
                    foreach(array_unique($send_to) as $send_to_mail) {
                        if( $send_to_mail != null & $send_to_mail != '' ) {
                            Mail::to($send_to_mail)->send(new AnniversaryMail( $employee )); 
                        }
                    }
                }
            }
        }
    }
}
