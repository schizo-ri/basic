<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee; 
use DateTime;
use App\Models\MailTemplate;

class IDCardExpiredMail extends Mailable
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','IDCardExpiredMail')->first();
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
        
        $date1 = new DateTime($this->employee->oi_expiry); 
        $date2 = new DateTime("now"); 
        $interval = $date1->diff($date2); 
        $days = $interval->format('%a');
        
        $subject = __('basic.ID_expiration') . ' - ' . $this->employee->first_name . ' ' .  $this->employee->last_name;

        return $this->view('emails.employees.id_expiration')
                    ->subject( $subject )
                    ->with([
                        'employee' => $this->employee,
                        'days' => $days,
                        'template_mail' => $mail_template,
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
