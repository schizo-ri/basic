<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Notice; 
use App\Models\MailTemplate;

class NoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The emplyee instance.
     *
     * @var vacationRequest
     */
    public $notice;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Notice $notice)
    {
        $this->notice = $notice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','NoticeMail')->first();
        
        $title = 'Nova ' . ' ' .  ' obavijest';
        if($this->notice->title) {
            $title .= $this->notice->title;
        } 

        return $this->view('Centaur::email.notice_send1')
                    ->subject( $title )
                    ->with(['notice'    => $this->notice,
                            'url'       => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/dashboard',
                            'template_mail' => $mail_template
                    ]);
    }
}