<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Task; 
use DateTime;
use App\Models\MailTemplate;
use Log;

class TaskInfoMail extends Mailable
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
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TaskInfoMail')->first();
        Log::info('TaskInfoMail');

        return $this->view('emails.tasks.info')
                    ->subject('Novi zadatak')
                    ->with([
                        'task'  => $this->task,
                        'template_mail' => $mail_template
                    ]);
    }
}
