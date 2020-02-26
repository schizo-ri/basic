<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\AbsenceCronMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Emailing;
use App\Models\Department;
use App\Models\Employee;

class Employee_absence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:absence_day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Izostanci';

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
    public function handle(AbsenceCronMail $abs)
    {
        $emailings = Emailing::get();
		$send_to = array();
		$departments = Department::get();
		$employees = Employee::where('id','<>',1)->where('checkout',null)->get();

		if(isset($emailings)) {
			foreach($emailings as $emailing) {
				if($emailing->table['name'] == 'absences' && $emailing->method == 'cron') {	
					if($emailing->sent_to_dep) {
						foreach(explode(",", $emailing->sent_to_dep) as $prima_dep) {
							array_push($send_to, $departments->where('id', $prima_dep)->first()->email );
						}
					}
					if($emailing->sent_to_empl) {
						foreach(explode(",", $emailing->sent_to_empl) as $prima_empl) {
							array_push($send_to, $employees->where('id', $prima_empl)->first()->email );
						}
					}
				}
			}
        }

        
        
        foreach(array_unique($send_to) as $send_to_mail) {
			if( $send_to_mail != null & $send_to_mail != '' ) {
                Mail::to($send_to_mail)->send(new AbsenceCronMail()); // mailovi upisani u mailing 
            }
		}

    }
}
