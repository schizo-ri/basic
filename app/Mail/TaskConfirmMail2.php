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

class TaskConfirmMail2 extends Mailable
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
        $mail_style = array();
        $template_text_header = array();
        $template_text_body= array();
        $template_text_footer = array();

        if( $mail_template ) {
            $mail_style = $mail_template->mailStyle;
            $template_text_header = MailTemplate::textHeader( $mail_template );
            $template_text_body = MailTemplate::textBody( $mail_template );
            $template_text_footer = MailTemplate::textFooter( $mail_template );
        }

        if( $this->employeeTask->status == 1) {
            $title = 'Potvrda '.' izvršenja '.' zadatka';
            $status = 'potvrdio';
            $status2 = 'Potvrdio si';
        } else {
            $title = 'Poništenje '.' potvrde '.' izvršenja '.' zadatka';
            $status = 'poništio potvrdu za';
            $status2 = 'Poništio si potvrdu za';
        } 

        return $this->view('emails.tasks.confirm2')
                    ->subject( $title )
                    ->with([
                        'employee_task'  => $this->employeeTask,
                        'status' => $status,
                        'status2' => $status2,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
