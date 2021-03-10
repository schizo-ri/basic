<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignSequence;
use App\Models\Department;
use Sentinel;
use App\Mail\CampaignMail;
use Illuminate\Support\Facades\Mail;

class CampaignController extends Controller
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
        $campaigns = Campaign::get();
        $campaign_sequences = CampaignSequence::get();

		return view('Centaur::campaigns.index', ['campaigns' => $campaigns,'campaign_sequences' => $campaign_sequences]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments0 = Department::where('level1',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level1',1)->orderBy('name','ASC')->get();
        $departments2 = Department::where('level1',2)->orderBy('name','ASC')->get();
        
        return view('Centaur::campaigns.create',['departments0' => $departments0, 'departments1' => $departments1, 'departments2' => $departments2]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array(
			'name'  		=> $request['name'],
			'description'  	=> $request['description'],
			'type'          => $request['type'],
		    'start_date'  	=> $request['start_date'],
			'start_time'  	=> $request['start_time'],
			'active'  	    => $request['active']
		);
		
		$campaign = new Campaign();
        $campaign->saveCampaign($data);
   
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
        $campaignSequences = CampaignSequence::where('campaign_id',$id )->get();

		return view('Centaur::campaigns.show', ['campaign' => $campaign, 'campaignSequences' => $campaignSequences]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = Campaign::find( $id );

        return view('Centaur::campaigns.edit', ['campaign' => $campaign ]);
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
        $campaign = Campaign::find( $id );

        $data = array(
			'name'  		=> $request['name'],
			'description'  	=> $request['description'],
			'type'          => $request['type'],
		    'start_date'  	=> $request['start_date'],
			'start_time'  	=> $request['start_time'],
			'active'  	    => $request['active']
		);
	
        $campaign->updateCampaign($data);

        session()->flash('success',__('ctrl.data_edit'));
		
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
        $campaign = Campaign::find( $id );
        $campaign_sequences = CampaignSequence::where('campaign_id', $campaign->id)->get();

        foreach ($campaign_sequences as $campaign_sequence) {
            $campaign_sequence->delete();
        }

        $campaign->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }

    public function startCampaign(Request $request) 
	{        
        /* mail obavijest o novoj poruci */
        $campaign = Campaign::find($request['id']);
        $campaign_sequence = CampaignSequence::where('campaign_id', $campaign->id)->orderBy('created_at','ASC')->get();

        if( count($campaign_sequence) > 0 ) {
            $send_to = Employee::getEmails();
            foreach(array_unique($send_to) as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new CampaignMail( $campaign ));
                }
            }
    
            $message = session()->flash('success', __('emailing.campaign_start'));
            
            return redirect()->back()->withFlashMessage($message);
        } else {
            $message = session()->flash('error', __('emailing.campaign_error'));
            
            return redirect()->back()->withFlashMessage($message);
        }
	}
}