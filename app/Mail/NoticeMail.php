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
        
        $title = ''; 
        if($this->notice->title) {
            $text = $this->notice->title;
            $text = explode(" ", $text);
            foreach ($text as $word) {
                $title .= $word . ' ';
            }
        } else {
            $title = 'Nova ' . ' ' .  ' obavijest';
        }

        return $this->view('Centaur::email.notice_send1')
                    ->subject( __('emailing.new_notice') . ' - ' . $title )
                    ->with(['notice'    => $this->notice,
                            'url'       => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/dashboard',
                            'template_mail' => $mail_template
                    ]);
    }
}