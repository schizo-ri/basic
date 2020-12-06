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
        
        $link = 'https://duplico.myintranet.io';
        $podrska = 'itpodrska@duplico.hr';

        return $this->view('emails.users.create')
                    ->subject( __('basic.access_data'))
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password,
                        'link' =>  $link,
                        'podrska' => $podrska,
                        'template_mail' => $mail_template
                    ]);
    } 
}