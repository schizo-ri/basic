<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MailTemplate;
use App\Models\KeyResult;

class KeyResultReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /** 
     * The keyResult instance.
     *
     * @var keyResult
     */
    public $keyResult;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(KeyResult $keyResult)
    {
        $this->keyResult = $keyResult;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','KeyResultReminderMail')->first();
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

        return $this->view('emails.key_results.reminder')
                    ->subject( 'Ključni ' . ' rezultati - podsjetnik' )
                    ->with([
                        'keyResult'     => $this->keyResult,
                        'template_mail' => $mail_template,
                        'mail_style'    => $mail_style,
                        'text_header'   => $template_text_header,
                        'text_body'     => $template_text_body,
                        'text_footer'   => $template_text_footer
                    ]);
    }
}