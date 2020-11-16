<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notice;
use App\Models\Employee;
use App\Models\Department;
use App\Mail\NoticeMail;
use Illuminate\Support\Facades\Mail;
use Log;

class NoticeSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notice schedule';

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
    public function handle(NoticeMail $noticeMail)
    {
        $notices = Notice::get();
        Log::info('NoticeSchedule');
         if(count($notices) > 0) {
            $employees = Employee::employees_lastNameASC();
    
            foreach ($notices as $notice) {
                if( date('Y-m-d', strtotime($notice->schedule_date)) == date('Y-m-d') ) {
                    $departments_id = explode(',',  $notice->to_department );
                  
                    foreach ($departments_id as $department_id) {
                        $allDepartmentsEmployeesEmail = Department::allDepartmentsEmployeesEmail($department_id);
                        try {
                            foreach ($allDepartmentsEmployeesEmail as $mail) {
                                Mail::to($mail)->send(new NoticeMail($notice));
                            }      
                        } catch (\Throwable $th) {
                            $message = session()->flash('success',  __('emailing.not_send'));
                            return redirect()->back()->withFlashMessage($message);
                        }
                    }
                }
            }
        } 
    }
}
