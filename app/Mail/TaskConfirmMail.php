<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmployeeTask; 
use DateTime;
use App\Models\MailTemplate;
use Log;

class TaskConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The employeeTask instance.
     *
     * @var employeeTask;

    */
    public $employeeTask;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeTask $employeeTask)
    {
        $this->employeeTask = $employeeTask;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TaskConfirmMail')->first();
        Log::info('TaskConfirmMail');

        if( $this->employeeTask->status == 1) {
            $title = 'Potvrda '.' izvršenja '.' zadatka';
            $status = 'potvrdio';
            $status2 = 'Potvrdio si';
        } else {
            $title = 'Poništenje '.' potvrde '.' izvršenja '.' zadatka';
            $status = 'poništio potvrdu za';
            $status2 = 'Poništio si potvrdu za';
        } 
        Log::info("status: ".$this->employeeTask->status);
        Log::info('title '.$title );
        Log::info('status1 '.$status );
        Log::info('status2 '.$status2 );
        
        return $this->view('emails.tasks.confirm')
                    ->subject( $title )
                    ->with([
                        'employee_task'  => $this->employeeTask,
                        'status' => $status,
                        'status2' => $status2,
                        'template_mail' => $mail_template
                    ]);
    }
}
