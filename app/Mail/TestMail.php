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
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template_mail = MailTemplate::where('for_mail', 'AbsenceMail')->first(); 

        return $this->view('emails.test')
                    ->subject( 'Test mail' )
                    ->with([
						'template_mail' => $template_mail
					]);
    }
}
