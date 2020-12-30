<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Afterhour;
use App\Models\MailTemplate;

class AfterHourSendMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The campaign instance.
     *
     * @var Campaign
     */
    public $afterhour;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Afterhour $afterhour )
    {
        $this->afterhour = $afterhour;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','AfterHourSendMail')->first();
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

        return $this->view('emails.afterhours.send')
                    ->subject( __('basic.request'))
                    ->with([
                        'afterhour' =>  $this->afterhour,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
