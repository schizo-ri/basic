<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Afterhour;
use App\Models\Employee;
use App\Models\Project;
use App\Mail\AfterHourCreateMail;
use App\Mail\AfterHourApproveMail;
use App\Mail\AfterHourInfoMail;
use App\Mail\AfterHourSendMail;
use Illuminate\Support\Facades\Mail;
use Sentinel;

class AfterhourController extends Controller
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
    public function index( Request $request)
    {
        $permission_dep = DashboardController::getDepartmentPermission();
        if( isset($request['date'])) {
            $date = explode('-', $request['date']);
            $month = $date[1];
            $year = $date[0];
        } else {
            $month = date('m');
            $year = date('Y');
        }
     
        if( isset($request['employee_id']) && $request['employee_id'] ) {
            $employee_id = $request['employee_id'];
           
        } else {
            $employee_id = null;
        }

        $employees = array();
        
        $afterhours = Afterhour::get();
        if(  $employee_id != null) {
            $afterhours = $afterhours->where('employee_id', $employee_id);
        }
       
        $dates = array();
        foreach (array_keys($afterhours->groupBy('date')->toArray()) as $date) {
            array_push($dates, date('Y-m',strtotime($date)) );
        }

        $dates = array_unique($dates);
        rsort($dates);
        $afterhours = $afterhours->filter(function ($afterhour, $key) use($month, $year) {
            return date('m',strtotime($afterhour->date)) == $month &&  date('Y',strtotime($afterhour->date)) == $year;
        });
        foreach ($afterhours as $afterhour) {
            array_push($employees, $afterhour->employee);
        }
        $employees = array_unique($employees);
       
        return view('Centaur::afterhours.index', ['afterhours' => $afterhours,'employees' => $employees, 'dates' => $dates, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::employees_firstNameASC();
        $projects = Project::where('active',1)->get();

        return view('Centaur::afterhours.create',['employees' => $employees,'projects' => $projects]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(is_array($request['employee_id']) && count($request['employee_id'])>0) {
			foreach($request['employee_id'] as $employee_id){
                $data = array(
                    'employee_id'  		=> $employee_id,
                    'project_id'  		=> $request['project_id'],
                    'date'    			=> $request['date'],
                    'start_time'  		=> $request['start_time'],
                    'end_time'  		=> $request['end_time'],
                    'comment'  		=> $request['comment']
                );
                
                $afterHour = new Afterhour();
                $afterHour->saveAfterhour($data);
            }
        } else {
            $data = array(
                'employee_id'  		=> $request['employee_id'],
                'project_id'  		=> $request['project_id'],
                'date'    			=> $request['date'],
                'start_time'  		=> $request['start_time'],
                'end_time'  		=> $request['end_time'],
                'comment'  		=> $request['comment']
            );
            
            $afterHour = new Afterhour();
            $afterHour->saveAfterhour($data);
        }

        Mail::to($afterHour->employee->email)->send(new AfterHourSendMail($afterHour)); 

        $send_to = EmailingController::sendTo('afterhours', 'create');
        foreach(array_unique($send_to) as $send_to_mail) {
            if( $send_to_mail != null & $send_to_mail != '' )
                Mail::to($send_to_mail)->send(new AfterHourCreateMail($afterHour)); 
        }
           
        $superior = $afterHour->employee->work->firstSuperior;
        if($superior) {
            Mail::to($send_to_mail)->send(new AfterHourInfoMail($afterHour)); 
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $afterhour = Afterhour::find($id);
        $employees = Employee::employees_firstNameASC();
        $projects = Project::where('active',1)->get();

        return view('Centaur::afterhours.edit',['afterhour' => $afterhour,'employees' => $employees,'projects' => $projects]);
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
        $afterhour = Afterhour::find($id);

        $data = array(
            'employee_id'  		=> $request['employee_id'],
            'project_id'  		=> $request['project_id'],
            'date'    			=> $request['date'],
            'start_time'  		=> $request['start_time'],
            'end_time'  		=> $request['end_time'],
            'comment'  		    => $request['comment']
        );
        
        $afterhour->updateAfterhour($data);

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
        $afterhour = Afterhour::find($id);
        $afterhour->delete();

        session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back();
    }

    public function storeConf(Request $request)
    {
        $afterHour = Afterhour::find($request['id']);
        
		if($afterHour) {
			$employee = $afterHour->employee;
			$mail = $employee->email;
			
            $approve_employee = Sentinel::getUser()->employee;
	
			$data = array(
				'approve'  		    =>  $request['odobreno'],
				'approve_h'  		=>  $request['odobreno_h'],
				'approved_id'    	=>  $approve_employee ? $approve_employee->id : null,
				'approved_date'	    =>  date("Y-m-d")
			);
			
			$afterHour->updateAfterHour($data);
            
            $send_to = EmailingController::sendTo('afterhours', 'create');

            foreach(array_unique($send_to) as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' )
                    Mail::to($send_to_mail)->send(new AfterHourApproveMail($afterHour)); 
            }
            
			$message = session()->flash('success', 'Zahtjev je potvrđen');
		} else {
			$message = session()->flash('error', 'Zahtjev nije nađen');
		}
		return redirect()->route('dashboard')->withFlashMessage($message);
    }

}
