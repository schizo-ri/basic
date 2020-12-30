<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee;
use DateTime;
use App\Models\MailTemplate;

class AnniversaryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The campaign instance.
     *
     * @var Campaign
     */
    public $employee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Employee $employee )
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','AnniversaryMail')->first();
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

        $date_now = new DateTime('now'); 
        $date = new DateTime( $this->employee->reg_date); 
        $years = $date_now->format('Y') - $date->format('Y') ; 

        $dan = $date->format('d');
        if( $date_now->format('d') != $dan) {
            $date->modify('-5 days'); 
            $interval = $date_now->diff($date); 
            $br_dana = $interval->format('%d');
            $br_dana = $br_dana+5;
        } else {
            $br_dana = 0;
        }
        
        if( $br_dana == 0 ) {
            $dana = 'danas';
        } else {
            $dana =  'za ' . $br_dana . ' dana';
        } 
            
        return $this->view('emails.employees.anniversary')
                    ->subject( __('basic.anniversary') . ' - ' .  $this->employee->first_name . ' ' .  $this->employee->last_name)
                    ->with([
                        'employee' =>  $this->employee,
                        'dana' =>  $dana,
                        'years' =>  $years,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}