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
        
        if(is_array($this->request)) {
            return $this->view('emails.error.new_error')
            ->subject( "Prijava " . " greške" )
             ->with([
                 'request' =>  $this->request,
                 'user' => Sentinel::getUser()->first_name .' '. Sentinel::getUser()->last_name,
                 'user_mail' => Sentinel::getUser()->email,
                 'request_uri' => $this->request_uri,
                 'url' => $_SERVER['HTTP_HOST'],
                 'template_mail' => $mail_template
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
                 'template_mail' => $mail_template
             ]);
        }
    }
}
