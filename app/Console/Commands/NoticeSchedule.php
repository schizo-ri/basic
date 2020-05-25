<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notice;
use App\Models\Employee;
use App\Mail\NoticeMail;
use Illuminate\Support\Facades\Mail;

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
        $send_to_mail = 'jelena.juras@duplico.hr';
     
        $notices = Notice::get();

        // $notice = Notice::first();
        //  Mail::to($send_to_mail)->send(new NoticeMail($notice));
        
       /*  foreach ($notices as $notice) {
            Mail::to($send_to_mail)->send(new NoticeMail($notice));
        } */
        
         if(count($notices) > 0) {
            $prima = array();
            $employees = Employee::where('id','<>',1)->where('checkout', null)->get();
    
            foreach ($notices as $notice) {
                if( date('Y-m-d', strtotime($notice->schedule_date)) == date('Y-m-d') ) {
                    $departments_id = explode(',',  $notice->to_department );
                    foreach ($departments_id as $department_id) {
                        $department = Department::where('id', $department_id)->first();
                        foreach ($employees as $employee) {
                            if ( $employee->work->department_id == $department->id) {
                                array_push($prima, $employee->email );
                            } 
                        }      
                    }
                    try {
                        foreach ($prima as $mail) {
                            Mail::to($mail)->send(new NoticeMail($notice));
                        }      
                        Mail::to('jelena.juras@duplico.hr')->send(new NoticeMail($notice));              
                    } catch (\Throwable $th) {
                        $message = session()->flash('success',  __('emailing.not_send'));
                        return redirect()->back()->withFlashMessage($message);
                    }
                }
               
            }
        } 
    }

}
