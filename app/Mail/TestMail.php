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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TestMail')->first();
        
        return $this->view('emails.test')
                    ->subject( 'Test mail' )
                    ->with([
						'template_mail' => $this->mailTemplate,
                        'template_mail' => $mail_template
					]);
    }
}
