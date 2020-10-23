<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CampaignSequence;
use App\Models\Department;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\CampaignRecipient;

class CampaignRecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $campaign = Campaign::find($request['campaign_id']);  
        $employees = Employee::employees_firstNameASC();
        $departments0 = Department::where('level1',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level1',1)->orderBy('name','ASC')->get();
        $departments2 = Department::where('level1',2)->orderBy('name','ASC')->get();

        $campaign_recipients = CampaignRecipient::where('campaign_id', $campaign->id)->get();
      
        return view('Centaur::campaign_recipients.create',['campaign' => $campaign, 'employees' => $employees, 'campaign_recipients' => $campaign_recipients, 'departments0' => $departments0, 'departments1' => $departments1, 'departments2' => $departments2]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campaign_recipients = CampaignRecipient::where('campaign_id', $request['campaign_id'])->get();
       
        foreach ($campaign_recipients->where('employee_id','<>',null) as $recipient ) {
            if(! in_array($recipient->employee_id, $request['employee_id'] )) {
                $recipient->delete();
            }
        }
        foreach ($campaign_recipients->where('department_id','<>',null) as $recipient ) {
            if(! in_array($recipient->department_id, $request['department_id'] )) {
                $recipient->delete();
            }
        }
        if($request['employee_id']) {
            foreach ($request['employee_id'] as $employee_id) {
                $campaign_recipient = $campaign_recipients->where('employee_id',$employee_id )->first();
                if(! $campaign_recipient ) {
                    $data = array(
                        'campaign_id'  => $request['campaign_id'],
                        'employee_id'  => $employee_id,
                    );
                        
                    $campaignRecipient = new CampaignRecipient();
                    $campaignRecipient->saveCampaignRecipient($data);
                }
            }
        }
        if($request['department_id']) {
            foreach ($request['department_id'] as $department_id) {
                $campaign_recipient = $campaign_recipients->where('department_id',$department_id )->first();
                if(! $campaign_recipient ) {
                    $data = array(
                        'campaign_id'  => $request['campaign_id'],
                        'department_id'  => $department_id,
                    );
                        
                    $campaignRecipient = new CampaignRecipient();
                    $campaignRecipient->saveCampaignRecipient($data);
                }
            }
        }

        

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
