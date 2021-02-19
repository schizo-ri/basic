<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailingController;
use App\Models\TemporaryEmployeeRequest;
use App\Models\TemporaryEmployee;
use App\Models\Employee;
use App\Models\AbsenceType;
use Sentinel;
use App\Mail\TemporaryEmployeeAbsenceMail;
use App\Mail\TemporaryEmployeeAbsenceConfirmMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;
use Log;


class TemporaryEmployeeRequestController extends Controller
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
        $permission_dep = DashboardController::getDepartmentPermission();
        $temporary_employee_requests = TemporaryEmployeeRequest::get();

        return view('Centaur::temporary_employee_requests.index', ['temporary_employee_requests' => $temporary_employee_requests, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = TemporaryEmployee::where('checkout','<>',1)->get();
		$absenceTypes = AbsenceType::where('temp',1)->get();
		
		$user = Sentinel::getUser();
		
        return view('Centaur::temporary_employee_requests.create', ['employees' => $employees, 'absenceTypes' => $absenceTypes, 'user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $absenceType = AbsenceType::where('mark',$request['type'])->first()->id;
        $data = array(
            'type'  			=> $absenceType,
            'employee_id'  		=> $request['employee_id'],
            'start_date'    	=> date("Y-m-d", strtotime($request['start_date'])),
            'end_date'			=> date("Y-m-d", strtotime($request['end_date'])),
            'start_time'  		=> $request['start_time'],
            'end_time'  		=> $request['end_time'],
            'comment'  			=> $request['comment'],
        );

        $temporaryEmployeeRequest = new TemporaryEmployeeRequest();
        $temporaryEmployeeRequest->saveTemporaryEmployeeRequest($data);

        $send_to = EmailingController::sendTo('temporary_employee_requests', 'create');
      /*   try { */
            foreach(array_unique($send_to) as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new TemporaryEmployeeAbsenceMail($temporaryEmployeeRequest));
                }
            }
       /*  } catch (\Throwable $th) {
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 

            session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
			return redirect()->back();
        } */
		
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
        $temporaryEmployeeRequest = TemporaryEmployeeRequest::find($id);
        $employees = TemporaryEmployee::where('checkout','<>',1)->get();
        $absenceTypes = AbsenceType::where('temp',1)->get();
        
        return view('Centaur::temporary_employee_requests.edit', ['temporaryEmployeeRequest' => $temporaryEmployeeRequest,'employees' => $employees, 'absenceTypes' => $absenceTypes]);
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
        Log::info($request);
        $temporaryEmployeeRequest = TemporaryEmployeeRequest::find($id);
        $absenceType = AbsenceType::where('mark',$request['type'])->first()->id;

        $data = array(
            'type'  			=> $absenceType,
            'employee_id'  		=> $request['employee_id'],
            'start_date'    	=> date("Y-m-d", strtotime($request['start_date'])),
            'end_date'			=> date("Y-m-d", strtotime($request['end_date'])),
            'start_time'  		=> $request['start_time'],
            'end_time'  		=> $request['end_time'],
            'comment'  			=> $request['comment'],
        );
        Log::info($data);
        $temporaryEmployeeRequest->updateTemporaryEmployeeRequest($data);
        Log::info($temporaryEmployeeRequest);
        $send_to = EmailingController::sendTo('temporary_employee_requests', 'create');
        Log::info($send_to);
        /*   try { */
              foreach(array_unique($send_to) as $send_to_mail) {
                  if( $send_to_mail != null & $send_to_mail != '' ) {
                      Mail::to($send_to_mail)->send(new TemporaryEmployeeAbsenceMail($temporaryEmployeeRequest));
                  }
              }
         /*  } catch (\Throwable $th) {
              $email = 'jelena.juras@duplico.hr';
              $url = $_SERVER['REQUEST_URI'];
              Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
  
              session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
              return redirect()->back();
          } */
          

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
        $temporaryEmployeeRequest = TemporaryEmployeeRequest::find($id);
        $temporaryEmployeeRequest->delete();
        
        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }

    public function storeConf(Request $request)
    {
		$temporaryEmployeeRequest = TemporaryEmployeeRequest::find($request['id']);
		$odobrio_user = Sentinel::getUser()->employee;
        
		$data = array(
			'approve'  			=>  $_GET['approve'],
			'approved_id'    	=>  $odobrio_user ? $odobrio_user->id : 3,
			'approve_reason'  	=>  $_GET['approve_reason'],
			'approved_date'		=>  date('Y-m-d')
		);
				
		$temporaryEmployeeRequest->updateTemporaryEmployeeRequest($data);

	 	$send_to = EmailingController::sendTo('temporary_employee_requests', 'confirm');
        $employee_mail = $temporaryEmployeeRequest->employee->email;
		array_push($send_to, $employee_mail ); // mail zaposlenika
        
        try {
            foreach(array_unique($send_to) as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new TemporaryEmployeeAbsenceConfirmMail($temporaryEmployeeRequest)); // mailovi upisani u mailing 
                }
            } 
        } catch (\Throwable $th) {
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
            
            session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
			return redirect()->back();
        }
				
		$message = session()->flash('success', __('absence.approved'));

		return redirect()->route('dashboard')->withFlashMessage($message);
    }
}