<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Instruction; 
use App\Models\MailTemplate;

class IstructionMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The istruction instance.
     *
     * @var istruction
     */
    public $istruction;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Instruction $instruction)
    {
        $this->instruction = $instruction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','IstructionMail')->first();
        
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/radne_upute';

        return $this->view('emails.instructions.create')
                    ->subject( __('ctrl.instruction_create') )
                    ->with([
                        'instruction' => $this->instruction,
                        'link'       => $link,
                        'template_mail' => $mail_template

                    ]);
    }
}
