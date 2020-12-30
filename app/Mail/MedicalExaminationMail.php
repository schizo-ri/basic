<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee; 
use DateTime;
use App\Models\MailTemplate;

class MedicalExaminationMail extends Mailable
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','MedicalExaminationMail')->first();
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
        
        $date1 = new DateTime($this->employee->lijecn_pregled); 
        $date2 = new DateTime("now"); 
        $interval = $date1->diff($date2); 
        $days = $interval->format('%a');
        
        return $this->view('emails.employees.medical_examination')
                    ->subject( __('basic.medical_examination') . ' - ' . $this->employee->first_name . ' ' .  $this->employee->last_name )
                    ->with([
                        'employee' => $this->employee,
                        'days' => $days,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
