<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Models\MailTemplate;

class UserCreateMail extends Mailable
{
    use Queueable, SerializesModels;

   /**
     * The user instance.
     *
     * @var vacationRequest
     */
    public $user;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','UserCreateMail')->first();
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
        
        $link = 'https://duplico.myintranet.io';
        $podrska = 'itpodrska@duplico.hr';

        return $this->view('emails.users.create')
                    ->subject( __('basic.access_data'))
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password,
                        'link' =>  $link,
                        'podrska' => $podrska,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    } 
}