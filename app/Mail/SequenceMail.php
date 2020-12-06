<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\CampaignSequence;
use App\Models\MailTemplate;

class SequenceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The sequence instance.
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','SequenceMail')->first();
        
        return $this->view('Centaur::campaign_sequences.campaign_mail')
                    ->subject($this->sequence->subject )
                    ->with([
                        'campaign_sequence' =>  $this->sequence,
                        'template_mail' => $mail_template
                    ]);
    }
}
