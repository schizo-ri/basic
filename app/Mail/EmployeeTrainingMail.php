<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmployeeTraining;
use App\Models\MailTemplate;

class EmployeeTrainingMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The employeeTraining instance.
     *
     * @var employeeTraining;

     */
    public $employeeTraining;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeTraining $employeeTraining)
    {
        $this->employeeTraining = $employeeTraining;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','EmployeeTrainingMail')->first();
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
        
        return $this->view('emails.employee_training.expiry')
                    ->subject( __('emailing.expiry_training') . ' ' . $this->employeeTraining->employee->user->first_name . ' ' . $this->employeeTraining->employee->user->last_name  )
                    ->with([
                        'employeeTraining' => $this->employeeTraining,
                        'template_mail' => $mail_template,
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}