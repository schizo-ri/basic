<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Campaign;
use App\Models\CampaignSequence;

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
       /*  $campaign_sequences = $this->campaign->campaignSequence->toArray();

        $campaign_sequences = CampaignSequence::where('campaign_id',  $this->campaign->id)->orderBy('created_at','ASC')->get();
        $first_sequence = $campaign_sequences->first();
                 */
       /*  return $this->markdown('emails.campaign.template')
                    ->subject($this->campaign->name)
                    ->with([
                        'first_sequence' => $first_sequence]); */

        return $this->from('info@duplico.hr', 'Duplico')
					->view('Centaur::campaign_sequences.campaign_mail')
					->subject($this->campaignSequence->subject )
					->with([
						'campaign_sequence' =>  $this->campaignSequence
					]);
    }
}
