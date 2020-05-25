<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use App\DripEmailer;
use App\Models\Campaign;
use App\Models\CampaignSequence;
use App\Models\Employee;
use App\Models\Department;
use App\Models\CampaignRecipient;
use App\Mail\SequenceMail;
use Illuminate\Support\Facades\Mail;
use DateTIme;

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
        $today = new DateTime();
        $today_date = date_format($today,'Y-m-d');

        $send_to_mail = 'jelena.juras@duplico.hr';
        
        $campaigns = Campaign::where('active',1)->get();
        $campaign_sequences_all = CampaignSequence::orderBy('order','ASC')->get();

        $employees = Employee::where('checkout', null)->get();  //where('id','<>',1)->
        // kampanje koje idu na određeni datum
        foreach ($campaigns->where('type','one_time') as $campaign) {
            $campaign_start = new DateTime($campaign->start_date);
            $campaign_start_date = date_format($campaign_start,'Y-m-d');
            if($campaign_start <= $today) {
                $campaign_sequences = $campaign_sequences_all->where('campaign_id', $campaign->id);
                if(count( $campaign_sequences) >0 ) {
                    foreach ($campaign_sequences as $sequence) {
                        $time_shift = explode('-',$sequence->send_interval);
                        $sequence_date = $campaign_start->modify('+'. $time_shift[0] . ' ' .$time_shift[1] );
                        if ( date_format($sequence_date,'Y-m-d') == $today_date ) {
                            foreach ($employees as $employee) {
                                Mail::to($employee->email)->send(new SequenceMail( $sequence ));   
                            }   
                        }
                    }
                }
            }        
        }

        // kampanje evergreen koje idu na određenog djelatnika - podešenu prilikom kreiranja djelatnika - zapisano u CampaignRecipient
        foreach ($campaigns->where('type','evergreen') as $campaign) {
            $campaign_sequences = $campaign_sequences_all->where('campaign_id', $campaign->id);
            $campaignRecipients = CampaignRecipient::where('campaign_id', $campaign->id )->where('employee_id','<>', null)->get();
            
            if( count( $campaignRecipients ) > 0 && count($campaign_sequences) >0 ) {
                foreach ($campaignRecipients as $recipient ) {
                    $campaign_start = new DateTime($recipient->created_at); //  datum kreiranja primatelja!!!
                    $campaign_start_date = date_format($campaign_start,'Y-m-d');
                    foreach ($campaign_sequences as $sequence) {
                        $time_shift = explode('-', $sequence->send_interval);
                        $sequence_date = $campaign_start->modify('+'. $time_shift[0] . ' ' .$time_shift[1] );
                        if ( date_format($sequence_date,'Y-m-d') == $today_date ) {
                            $employee = $recipient->employee;
                            Mail::to($employee->email)->send(new SequenceMail( $sequence ));  
                        }
                    }
                }
            }

            $campaignRecipients_dep = CampaignRecipient::where('campaign_id', $campaign->id )->where('department_id','<>', null)->get();
            if( count( $campaignRecipients_dep ) > 0 && count($campaign_sequences) >0 ) {
                foreach ($campaignRecipients_dep as $recipient_dep ) {
                    $campaign_start = new DateTime($recipient_dep->created_at); //  datum kreiranja primatelja!!!
                    $campaign_start_date = date_format($campaign_start,'Y-m-d');
                    $department = Department::where('id',$recipient_dep->department_id )->first();
                    foreach ($campaign_sequences as $sequence) {
                        $time_shift = explode('-', $sequence->send_interval);
                        $sequence_date = $campaign_start->modify('+'. $time_shift[0] . ' ' .$time_shift[1] );
                        if ( date_format($sequence_date,'Y-m-d') == $today_date ) {
                            foreach ($employees as $employee) {
                                if($employee->work->department->id == $department->id ) {
                                    Mail::to($employee->email)->send(new SequenceMail( $sequence ));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}