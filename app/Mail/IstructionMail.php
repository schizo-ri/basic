<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Instruction; 

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
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/radne_upute';

        return $this->markdown('emails.instructions.create')
                    ->subject( __('ctrl.instruction_create') )
                    ->with([
                        'instruction' => $this->instruction,
                        'link'       => $link,

                    ]);
    }
}
