<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CampaignSequence;
use App\Models\Department;
use App\Models\Campaign;
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
  
        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 

		return view('Centaur::campaign_sequences.index', ['campaign_sequences' => $campaign_sequences,'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $campaigns = Campaign::get();
  
        $this_campaign = null;
        $campaign_sequences = array();

        if( $request['id'] ) {
            $this_campaign = Campaign::find( $request['id'] );
            $campaign_sequences = $this_campaign->campaignSequence->toArray();
        }
       
        return view('Centaur::campaign_sequences.create',['campaigns' => $campaigns,'this_campaign' => $this_campaign,'campaign_sequences' => $campaign_sequences]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request['interval'] != null) {
            $send_interval = $request['interval'] . '-' .$request['period'] ;
        } else {
            $send_interval = $request['send_interval'];
        }
        
        $data = array(
			'campaign_id'  => $request['campaign_id'],
			'text'  	    => $request['text'],
			'start_date'  	=> $request['start_date'],
			'send_interval' => $send_interval
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
        //
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
        $campaigns = Campaign::get();

        if(strpos($campaign_sequence['send_interval'], '-')) {
            $send_interval = explode('-', $campaign_sequence['send_interval']);  // 1-day
            $period = $send_interval[0];   // 1
            $interval = $send_interval[1];   // day
        } else {
            $send_interval = $campaign_sequence['send_interval'];
            $period = null;   
            $interval = $send_interval[1]; 
        }
     
        return view('Centaur::campaign_sequences.edit',['campaigns' => $campaigns, 'campaign_sequence' => $campaign_sequence, 'send_interval' => $send_interval]);
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
        
        if($request['interval'] != null) {
            $send_interval = $request['interval'] . '-' .$request['period'] ;
        } else {
            $send_interval = $request['send_interval'];
        }
       
        $data = array(
			'campaign_id'  => $request['campaign_id'],
			'text'  	    => $request['text'],
			'start_date'  	=> $request['start_date'],
			'send_interval' => $send_interval
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
}
