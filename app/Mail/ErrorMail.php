<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sentinel;

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
        if(is_array($this->request)) {
            $request = implode("\r\n",$this->request );
        } else {
            $request = $this->request;
        }
        return $this->markdown('email.error.new_error')
                    ->subject( "Prijava " . " greške" )
                    ->with([
                        'request' => $request,
                        'user' => Sentinel::getUser()->first_name .' '. Sentinel::getUser()->last_name,
                        'request_uri' => $this->request_uri,
                        'url' => $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]
                    ]);
    }
}
