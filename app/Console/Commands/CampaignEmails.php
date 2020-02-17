<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use App\DripEmailer;
use App\Models\Campaign;
use App\Models\CampaignSequence;
use App\Mail\CampaignMail;
use Illuminate\Support\Facades\Mail;

class CampaignEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Campaign';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $send_to_mail = 'jelena.juras@duplico.hr';
        $campaigns = Campaign::get();
        $campaign_sequences_all = CampaignSequence::orderBy('created_at','ASC')->get();

        foreach ($campaigns as $campaign) {
            $campaign_sequences = $campaign_sequences_all->where('campaign_id', $campaign->id)->get();
            foreach ($campaign_sequences as $campaign_sequence) {
                $first_sequnce = $campaign_sequence->first();
                $date_first_sequnce = $first_sequnce->start_date;
            }
            

            Mail::to($send_to_mail)->send(new CampaignMail( $campaign ));
        }        
    }
}
