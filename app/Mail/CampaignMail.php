<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Campaign;
use App\Models\CampaignSequence;
use App\Models\MailTemplate;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The campaign instance.
     *
     * @var Campaign
     */
    public $campaignSequence;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CampaignSequence $campaignSequence)
    {
        $this->campaignSequence = $campaignSequence;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','CampaignMail')->first();
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
					->subject($this->campaignSequence->subject )
					->with([
						'campaign_sequence' =>  $this->campaignSequence,
                        'template_mail' => $mail_template,
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
					]);
    }
}