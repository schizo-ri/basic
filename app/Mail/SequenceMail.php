<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\CampaignSequence;

class SequenceMail extends Mailable
{
    use Queueable, SerializesModels;

    
    /**
     * The emplyee instance.
     *
     * @var vacationRequest
     */
    public $sequence;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CampaignSequence $sequence)
    {
        $this->sequence = $sequence;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Centaur::campaign_sequences.campaign_mail')
                    ->subject($this->sequence->subject )
                    ->with([
                        'campaign_sequence' =>  $this->sequence
                    ]);
    }
}
