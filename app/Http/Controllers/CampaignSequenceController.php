<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CampaignSequenceRequest;
use App\Http\Controllers\Controller;
use App\Models\CampaignSequence;
use App\Models\Department;
use App\Models\Campaign;
use App\Models\Template;
use App\Mail\SequenceMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;
use Sentinel;

class CampaignSequenceController extends Controller
{    
    /**
     *
     * Set middleware to quard controller.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('sentinel.auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaign_sequences = CampaignSequence::get();
  

		return view('Centaur::campaign_sequences.index', ['campaign_sequences' => $campaign_sequences]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $campaigns = Campaign::get();
        $templates = Template::get();
        $this_campaign = null;
      //  $campaign_sequences = array();

        if( $request['id'] ) {
            $this_campaign = Campaign::find( $request['id'] );
           /*  $campaign_sequences = CampaignSequence::where('campaign_id',$this_campaign->id )->orderBy('order','DESC')->get(); */
            $campaign_sequences = $this_campaign->campaignSequence->sortByDesc('order');
        }
      
        return view('Centaur::campaign_sequences.create',['campaigns' => $campaigns,'templates' => $templates,'this_campaign' => $this_campaign,'campaign_sequences' => $campaign_sequences]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
       /*  if(isset($request['interval']) && $request['interval'] != null) {
            $send_interval = $request['interval'] . '-' .$request['period'] ;
        } elseif( isset($request['send_interval']) && $request['send_interval']) {
            $send_interval = $request['send_interval'];
        } else {
            $send_interval = null;
        } */

        $sequences_count = count(CampaignSequence::where('campaign_id', $request['campaign_id'] )->get());

        $data = array(
		    'campaign_id'   => $request['campaign_id'],
			'subject'       => $request['subject'],
			'order'         => $sequences_count+1,
			'text'  	    => $request['text_html'],
			'text_json'  	=> $request['text_json'],
			'start_date'  	=> $request['start_date'],
			'send_interval' => $request['interval'] . '-' .$request['period'],
        );
        
        $campaignSequence = new CampaignSequence();
        $campaignSequence->saveCampaignSequence($data);
      
        session()->flash('success',  __('ctrl.data_save'));

		return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $campaign = Campaign::find($id);
        $campaignSequences = CampaignSequence::where('campaign_id',$id )->orderBy('order', 'ASC')->get();

        $empl = Sentinel::getUser()->employee;

		return view('Centaur::campaign_sequences.show', ['campaign' => $campaign, 'campaignSequences' => $campaignSequences]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign_sequence = CampaignSequence::find($id);
        //$campaigns = Campaign::get();
        $send_interval = null;

        $this_campaign = Campaign::find($campaign_sequence->campaign_id);
        $campaign_sequences = $this_campaign->campaignSequence;

        if($campaign_sequence['send_interval'] ) {
            if(strpos($campaign_sequence['send_interval'], '-')) {
                $send_interval = explode('-', $campaign_sequence['send_interval']);  // 1-day
                $period = $send_interval[0];   // 1
                $interval = $send_interval[1];   // day
            } else {
                $send_interval = $campaign_sequence['send_interval'];
                $period = null;   
                $interval = $send_interval[1]; 
            }
        }
        $templates = Template::get();
        return view('Centaur::campaign_sequences.edit',['this_campaign' => $this_campaign, 'templates' => $templates, 'campaign_sequence' => $campaign_sequence, 'campaign_sequences' => $campaign_sequences, 'send_interval' => $send_interval]);

     //   return response(view('Centaur::campaign_sequences.edit',array('text'=>$campaign_sequence->text, 'campaign_sequence'=>$campaign_sequence)),200, ['Content-Type' => 'application/json']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campaign_sequence = CampaignSequence::find($id);
        
/* 
        if(isset($request['interval']) && $request['interval'] != null) {
            $send_interval = $request['interval'] . '-' .$request['period'] ;
        } elseif( isset($request['send_interval']) && $request['send_interval']) {
            $send_interval = $request['send_interval'];
        } else {
            $send_interval = null;
        } */
        //$sequences_count = count(CampaignSequence::where('campaign_id', $request['campaign_id'] )->get());
        $data = array(
            'campaign_id'   => $request['campaign_id'],
            'subject'       => $request['subject'],
			'text'  	    => $request['text_html'],
			'text_json'  	=> $request['text_json'],
			'start_date'  	=> $request['start_date'],
			'send_interval' => $request['interval'] . '-' .$request['period'],
        );        
        
        $campaign_sequence->updateCampaignSequence($data);

        session()->flash('success',  __('ctrl.data_edit'));

		return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
  
        $campaign_sequence = CampaignSequence::find($id);

        $campaign_sequence->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();

    }

    public function campaign_mail(Request $request) 
    {        
        $campaign_sequence = CampaignSequence::find($request['sequence_id']);

        return view('Centaur::campaign_sequences.campaign_mail', ['campaign_sequence' => $campaign_sequence ]);
    }
    
    public function test_mail_open (Request $request)
    {
        return view('Centaur::campaign_sequences.test_mail',['sequence_id' => $request['id']]);
    }

    public function sendTestEmail(Request $request) 
	{
        $send_to = $request['recipient'];
        
        $sequence = CampaignSequence::find($request['sequence_id']);

        if( $send_to != null ) {
            try {
                Mail::to($send_to)->send(new SequenceMail( $sequence ));   
                
            } catch (\Throwable $th) {
                $email = 'jelena.juras@duplico.hr';
                $url = $_SERVER['REQUEST_URI'];
                Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
                
                $message = session()->flash('error', __('emailing.not_send'));
		        return redirect()->back()->withFlashMessage($message);
            }
        }

		$message = session()->flash('success', __('emailing.email_send'));
		
		return redirect()->back()->withFlashMessage($message);
    }

    public function setOrder( Request $request) 
    {
        $sequences_id = $request['sequences_id'];

        foreach ($sequences_id as $key => $sequence_id) {
            $sequence = CampaignSequence::find($sequence_id);
            $data = array(
                'order'  => $key +1,
            );        
            
            $sequence->updateCampaignSequence($data);
        }
        
        return session()->flash('success',  __('ctrl.data_edit'));
		
		//return redirect()->back()->withFlashMessage($message);
    }
}
