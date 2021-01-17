<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sentinel;
use App\Models\MailTemplate;

class ErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $url)
    {
        $this->request = $request;
        $this->request_uri = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','ErrorMail')->first();
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
        
        if(is_array($this->request)) {
            return $this->view('emails.error.new_error')
            ->subject( "Prijava " . " greške" )
             ->with([
                 'request' =>  $this->request,
                 'user' => Sentinel::getUser()->first_name .' '. Sentinel::getUser()->last_name,
                 'user_mail' => Sentinel::getUser()->email,
                 'request_uri' => $this->request_uri,
                 'url' => $_SERVER['HTTP_HOST'],
                 'template_mail' => $mail_template,
                 'mail_style' => $mail_style,
                 'text_header' => $template_text_header,
                 'text_body' => $template_text_body,
                 'text_footer' => $template_text_footer
             ]);
        } else {
            return $this->view('emails.error.new_error1')
            ->subject( "Prijava " . " greške" )
             ->with([
                 'request' =>  $this->request,
                 'user' => Sentinel::getUser()->first_name .' '. Sentinel::getUser()->last_name,
                 'user_mail' => Sentinel::getUser()->email,
                 'request_uri' => $this->request_uri,
                 'url' => $_SERVER['HTTP_HOST'],
                 'template_mail' => $mail_template,
                 'mail_style' => $mail_style,
                 'text_header' => $template_text_header,
                 'text_body' => $template_text_body,
                 'text_footer' => $template_text_footer
             ]);
        }
    }
}
