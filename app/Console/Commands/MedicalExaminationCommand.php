<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;
use App\Mail\MedicalExaminationMail;
use App\Http\Controllers\EmailingController;
use DateTime;
use Log;

class MedicalExaminationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medicalExamination';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Liječnički pregled';

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

        // Liječnički za 30 dana
        $datum = new DateTime('now');
		$datum->modify('+1 month');
		$employees = Employee::employeesMedicalExamination($datum);
        Log::info('MedicalExaminationCommand ' . ' | ' .  date_format($datum,'Y-m-d'));

		foreach($employees as $employee) {
            foreach($send_to as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new MedicalExaminationMail($employee)); 
                }
            }
        }
        
        // Liječnički za 15 dana
        $datum = new DateTime('now');
        $datum->modify('+15 days');
        $employees = Employee::employeesMedicalExamination($datum);
        Log::info('MedicalExaminationCommand ' . ' | ' .  date_format($datum,'Y-m-d'));

        foreach($employees as $employee) {
            foreach($send_to as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new MedicalExaminationMail($employee)); 
                }
            }
        }

        // Liječnički za 7 dana
        $datum = new DateTime('now');
        $datum->modify('+7 days');
        Log::info('MedicalExaminationCommand ' . ' | ' .  date_format($datum,'Y-m-d'));
		$employees = Employee::employeesMedicalExamination($datum);
		
		foreach($employees as $employee) {
            foreach($send_to as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new MedicalExaminationMail($employee)); 
                }
            }
        }

		$this->info('Obavijest je poslana!');
    }
}
