<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkCorrecting;
use App\Models\Employee;
use App\Models\Project;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;
use App\Mail\WorkCorrectingCreateMail;
use App\Mail\ErrorMail;
use Sentinel;
use Log;

class WorkCorrectingController extends Controller
{
    private $api_erp;

    /**
	*
	* Set middleware to quard controller.
	* @return void
	*/
	public function __construct()
	{
		$this->middleware('sentinel.auth');
        $this->api_erp = true;
        $this->test_mail = false;  // true - test na jelena.juras@duplico.hr
	}
    
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
    public function create()
    {
        $employees = Employee::employees_lastNameASCStatus(1);
        $projects = Project::where('active',1)->get();

        return view('Centaur::work_correctings.create', ['projects' => $projects,'employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message_erp = '';
        $data = array(
            'date'  	    => $request['date'],
			'project_id'  	=> $request['project_id'],
			'employee_id'  	=> $request['employee_id'],
            'user_id'  	    => Sentinel::getUser()->employee->id,
			'time'          => $request['time'],
            'comment'	 	=> $request['comment']
		);

        $workCorrecting = new WorkCorrecting();
		$workCorrecting->saveWorkCorrecting($data);

        if( $this->test_mail ) {
            $send_to = array('jelena.juras@duplico.hr');
        } else {
            $send_to = EmailingController::sendTo('work_correctings', 'create');
        }
        foreach(array_unique($send_to) as $send_to_mail) {
            if( $send_to_mail != null & $send_to_mail != '' ) {
                Mail::to($send_to_mail)->send(new WorkCorrectingCreateMail($workCorrecting)); 
            }
        }
	
		session()->flash('success',  __('ctrl.data_save') . ' ' .  $message_erp);
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
        $work_correcting = WorkCorrecting::find($id);
        $employees = Employee::employees_lastNameASCStatus(1);
        $projects = Project::where('active',1)->get();

        return view('Centaur::work_correctings.edit', ['work_correcting' => $work_correcting,'projects' => $projects,'employees' => $employees]);
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
        $work_correcting = WorkCorrecting::find($id);

        $data = array(
			'date'  	    => $request['date'],
			'project_id'  	=> $request['project_id'],
			'employee_id'  	=> $request['employee_id'],
            'user_id'  	    => Sentinel::getUser()->employee->id,
			'time'          => $request['time'],
            'comment'	 	=> $request['comment']
		);

		$work_correcting->updateWorkCorrecting($data);
		
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
        $work_correcting = WorkCorrecting::find($id);
        if ($work_correcting) {
            $work_correcting->delete();
        
            session()->flash('success',  __('ctrl.data_delete'));
            return redirect()->back();	
        } else {
            session()->flash('success',  __('ctrl.no_data'));
            return redirect()->back();
        }
    }

    public function storeConf(Request $request)
    {
        Log::info('**************** Popravak sati odobrenje storeConf*************');
        Log::info('poslan request:');
        Log::info($request);
       
        $approve_employee = Sentinel::getUser()->employee;
        $message_erp = '';
        
        $work_correcting = WorkCorrecting::find($request['id']);

        if( $work_correcting ) {
            $employee = $work_correcting->employee;
            $mail = $employee->email;
    
            $data = array(
                'approve'  		    =>  intval($request['approve']),
                'approve_h'  		=>  $request['approve'] == 0 ? '00:00:00' : $request['approve_h'],
                'approved_reason'  	=>  $request['approved_reason'] != '' ? $request['approved_reason']  : null,
                'approved_id'    	=>  $approve_employee ? $approve_employee->id : null,
                'approved_date'	    =>  date("Y-m-d")
            );
            
            $work_correcting->updateWorkCorrecting($data);
            Log::info('zapisano u bazu:');
            Log::info($work_correcting);
            	
            if( $this->api_erp ) {
                // slanje zahtjeva u Odoo
                try {
                    $api = new ApiController();
                    $send_leave_request = $api->send_leave_request($work_correcting, 'correct');
                    if($send_leave_request == true) {
                        $message_erp = 'Sati su uspješno zapisani u Odoo.';
                    } else {
                        $message_erp = 'Sati NISU zapisani u Odoo.';
                    }

                } catch (\Throwable $th) {
                    $email = 'jelena.juras@duplico.hr';
                    $url = $_SERVER['REQUEST_URI'];
                    Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 

                    session()->flash('error', __('ctrl.error') );
                    return redirect()->back();
                }
            }

            $message = session()->flash('success', 'Zahtjev je potvrđen. ' . $message_erp);
        } else {
            $message = session()->flash('error', __('ctrl.request_deleted'));
        }
        Log::info('**************** KRAJ  Popravak sati  odobrenje storeConf*************');
        return redirect()->route('dashboard')->withFlashMessage($message);
    }
}
