<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee;
use App\Models\MailTemplate;
use Log;


class BirthDayMail extends Mailable
{
    use Queueable, SerializesModels;

     /**
     * The emplyee instance.
     *
     * @var employee;

     */
    public $employee;
	
	/**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','BirthDayMail')->first();
        
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

        return $this->view('emails.employees.birthday')
                    ->subject( __('basic.b_day_employee') . ' - ' .  $this->employee->first_name . ' ' .  $this->employee->last_name)
                    ->with([
                        'employee' =>  $this->employee,
                        'mail_style' => $mail_style,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'mail_style' => $mail_style,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
