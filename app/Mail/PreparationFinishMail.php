<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Preparation;

class PreparationFinishMail extends Mailable
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
    public function __construct(Preparation $preparation)
    {
        $this->preparation = $preparation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      
        if( $this->preparation->finish == 1 ) {
            $subject = 'Ormar je spreman za isporuku';
            $text = ' je spreman za isporuku';
        } else {
            $subject = 'Poništenje ' . ' gotovosti ' .' ormara';
            $text = ' još nije spreman za isporuku';
        }
       
        return  $this->markdown('email.preparation.finish')
        ->subject(  $subject )
        ->with([
            'preparation' => $this->preparation,
            'text' => $text
        ]);
    }
}