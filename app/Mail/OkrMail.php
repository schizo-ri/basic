<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MailTemplate;
use App\Models\Okr;

class OkrMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The okr instance.
     *
     * @var okr
     */
    public $okr;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Okr $okr)
    {
        $this->okr = $okr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','OkrMail')->first();
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

        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/okrs';


        return $this->view('emails.okrs.create')
                    ->subject( 'Kreiran je novi OKR' )
                    ->with([
                        'okr'           => $this->okr,
                        'link'          => $link,
                        'template_mail' => $mail_template,
                        'mail_style'    => $mail_style,
                        'text_header'   => $template_text_header,
                        'text_body'     => $template_text_body,
                        'text_footer'   => $template_text_footer
                    ]);;
    }
}
