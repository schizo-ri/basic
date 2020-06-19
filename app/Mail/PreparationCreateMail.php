<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Preparation;

class PreparationCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The EquipmentList instance.
     *
     * @var vacationRequest
     */
    public $preparation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $preparations)
    {
        $this->preparations = $preparations;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Centaur::email.preparations')
        ->subject( 'Lista novih projekata')
        ->with([
            'preparations' => $this->preparations
        ]);
    }
}
