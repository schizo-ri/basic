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
        
        return $this->view('Centaur::campaign_sequences.campaign_mail')
                    ->subject($this->sequence->subject )
                    ->with([
                        'campaign_sequence' =>  $this->sequence,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
