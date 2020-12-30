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
        
        $title = 'Nova ' . ' ' .  ' obavijest';
        if($this->notice->title) {
            $title .= $this->notice->title;
        } 

        return $this->view('Centaur::email.notice_send1')
                    ->subject( $title )
                    ->with(['notice'    => $this->notice,
                            'url'       => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/dashboard',
                            'template_mail' => $mail_template,
                            'text_header' => $template_text_header,
                            'text_body' => $template_text_body,
                            'text_footer' => $template_text_footer
                    ]);
    }
}