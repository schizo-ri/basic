<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Comment;
use App\Models\MailTemplate;

class CommentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The comment instance.
     *
     * @var Campaign
     */
    public $comment;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','CommentMail')->first();
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
        
        if(isset( $_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != 'localhost') {
            $host =  $_SERVER['HTTP_HOST'];
        } else {
            $host = 'localhost:8000';
        }

        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $host  . '/posts';

        return $this->view('emails.comments.create')
                    ->subject('Dobio si novu poruku na myIntranet')
                    ->with([
                        'comment' =>  $this->comment,
                        'url' =>  $url,
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
