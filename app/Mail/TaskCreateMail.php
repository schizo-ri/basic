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

class TaskCreateMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The emplyee instance.
     *
     * @var employee;

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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TaskCreateMail')->first();
        Log::info('TaskCreateMail');

        return $this->view('emails.tasks.create')
                    ->subject('Novi zadatak')
                    ->with([
                        'employeeTask'  => $this->employeeTask,
                        'template_mail' => $mail_template
                    ]);
    }
}