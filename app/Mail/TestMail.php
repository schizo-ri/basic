<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MailTemplate;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The mailTemplate instance.
     *
     * @var mailTemplate
     */
    public $mailTemplate;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MailTemplate $mailTemplate)
    {
        $this->mailTemplate = $mailTemplate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_style = $this->mailTemplate->mailStyle;
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
        
        $variable = "Ovo je varijabla!!!";

        return $this->view('emails.test')
                    ->subject( 'Test mail' )
                    ->with([
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer,
                        'variable'  => $variable
					]);
    }
}
