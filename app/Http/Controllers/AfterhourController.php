<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BasicAbsenceController;
use App\Http\Controllers\ApiController;
use App\Models\Afterhour;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Absence;
use App\Mail\AfterHourCreateMail;
use App\Mail\AfterHourApproveMail;
use App\Mail\AfterHourInfoMail;
use App\Mail\AfterHourSendMail;
use App\Mail\AbsenceConfirmMail;
use Illuminate\Support\Facades\Mail;
use Sentinel;
use DateTime;
use Log;

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
        
        $employees = Employee::employees_lastNameASC();

        if(  $employee_id != null && $employee_id != 'all' ) {
            $afterhours = $afterhours->where('employee_id', $employee_id);
        }
        
        $dates = array();
        foreach (array_keys($afterhours->groupBy('date')->toArray()) as $date) {
            array_push($dates, date('Y-m',strtotime($date)) );
        }
        if( ! in_array( date('Y-m'), $dates)) {
            array_push($dates, date('Y-m'));
        }

        $dates = array_unique($dates);
        rsort($dates);
        $afterhours = $afterhours->filter(function ($afterhour, $key) use($month, $year) {
            return date('m',strtotime($afterhour->date)) == $month && date('Y',strtotime($afterhour->date)) == $year;
        });
       
        return view('Centaur::afterhours.index', ['afterhours' => $afterhours,'employees' => $employees, 'dates' => $dates, 'permission_dep' => $permission_dep]);
    }

    public function afterhours_approve( )
    {
        $afterhours = Afterhour::where('approve',null)->get();

        return view('Centaur::afterhours.afterhours_approve', ['afterhours' => $afterhours]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::employees_lastNameASC();
     
        $employee = Sentinel::getUser()->employee;
       /*  $api = new ApiController(); */
       $projects = Project::where('active',1)->get();
       $tasks = null;
       /*  if( $employee ) {
            $erp_id = $employee->erp_id;
            
            // $tasks = $api->get_employee_project_tasks( $erp_id, date('Y-m-d') ); 
           // $tasks = null;

           // $projects = null;
        } else {
          //  $tasks = null;
          //  $projects = Project::where('active',1)->get();
        } */
        
       /*  $leave_types = $api->get_available_leave_types(); */
        
        return view('Centaur::afterhours.create',['employees' => $employees,'projects' => $projects,'tasks' => $tasks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message_exist = '';
      
        if(is_array($request['employee_id']) && count($request['employee_id'])>0) {
			foreach($request['employee_id'] as $employee_id){
                $request_exist = BasicAbsenceController::afterhoursForDay($employee_id, $request['date'], $request['start_time'], $request['end_time'] );

                if( $request_exist == 0 ) {
                    $data = array(
                        'ERP_leave_type' => isset($request['ERP_leave_type']) ? $request['ERP_leave_type'] : 3,
                        'erp_task_id'    => isset($request['erp_task_id']) ? $request['erp_task_id'] : null,
                        'project_id'  	 => $request['project_id'],
                        'employee_id'  	 => $employee_id,
                        'date'    		 => $request['date'],
                        'start_time'  	 => $request['start_time'],
                        'end_time'  	 => $request['end_time'],
                        'comment'  		 => $request['comment']
                    );
                   
                    $afterHour = new Afterhour();
                    $afterHour->saveAfterhour($data);
                    
                    Mail::to($afterHour->employee->email)->send(new AfterHourSendMail($afterHour));
    
                    $send_to = EmailingController::sendTo('afterhours', 'create');
                    
                  /*   $send_to = array('jelena.juras@duplico.hr'); */
                    Log::info("Prekovremeni poslan na mail: ".implode(', ',array_unique($send_to)));
                                    
                    foreach(array_unique($send_to) as $send_to_mail) {
                        if( $send_to_mail != null & $send_to_mail != '' ) {
                            Mail::to($send_to_mail)->send(new AfterHourCreateMail($afterHour)); 
                        }
                    }
                    // za djelatnike Inženjeringa mail ide voditelju - Željko Rendulić
                    if( $afterHour->employee->work->department == 'Inženjering') {
                        $voditelj =  $afterHour->employee->work ? $afterHour->employee->work->employee : null;
                        if( $voditelj ) {
                            Log::info( 'Prekovremeni - info mail ide na  ' . $voditelj->user->first_name . ' '. $voditelj->user->last_name );
                            Mail::to( $voditelj->email)->send(new AfterHourInfoMail($afterHour)); 
                        }
                    }
                } 
            }
        } else {
            $request_exist = BasicAbsenceController::afterhoursForDay($request['employee_id'], $request['date'], $request['start_time'], $request['end_time'] );
            
            if( $request_exist == 0  ) {
                $data = array(
                    'ERP_leave_type' => isset($request['ERP_leave_type']) ? $request['ERP_leave_type'] : null,
                    'erp_task_id'    => isset($request['erp_task_id']) ? $request['erp_task_id'] : null,
                    'employee_id'  	 => $request['employee_id'],
                    'project_id'  	 => $request['project_id'],
                    'date'    		 => $request['date'],
                    'start_time'  	 => $request['start_time'],
                    'end_time'  	 => $request['end_time'],
                    'comment'  		 => $request['comment']
                );
                
                $afterHour = new Afterhour();
                $afterHour->saveAfterhour($data);
                
                Mail::to($afterHour->employee->email)->send(new AfterHourSendMail($afterHour));
    
                $send_to = EmailingController::sendTo('afterhours', 'create');
                
                /* $send_to = array('jelena.juras@duplico.hr'); */
                Log::info("Prekovremeni poslan na mail: ".implode(', ',array_unique($send_to)));
                                
                foreach(array_unique($send_to) as $send_to_mail) {
                    if( $send_to_mail != null & $send_to_mail != '' ) {
                        Mail::to($send_to_mail)->send(new AfterHourCreateMail($afterHour)); 
                    }
                }
                // za djelatnike Inženjeringa mail ide voditelju - Željko Rendulić
                if( $afterHour->employee->work->department == 'Inženjering') {
                    $voditelj =  $afterHour->employee->work ? $afterHour->employee->work->employee : null;
                    if( $voditelj ) {
                        Log::info( 'Prekovremeni - info mail ide na  ' . $voditelj->user->first_name . ' '. $voditelj->user->last_name );
                        Mail::to( $voditelj->email)->send(new AfterHourInfoMail($afterHour)); 
                    }
                }

                /* ZA SADA NE !!!!!   $superior = $afterHour->employee->work ? $afterHour->employee->work->firstSuperior : null;
                if($superior) {
                    Mail::to( $superior->email)->send(new AfterHourInfoMail($afterHour)); 
                }  */
            } else {
                session()->flash('error',  __('ctrl.request_exist'));
                return redirect()->back();
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
        $afterhour = Afterhour::find($id);
        $employees = Employee::employees_lastNameASC();
     
        $employee = Sentinel::getUser()->employee;
      /*   $api = new ApiController(); */

        /* if( $employee ) {
            $erp_id = $employee->erp_id;
            
            $tasks = $api->get_employee_project_tasks( $erp_id, date('Y-m-d') ); 
            $tasks = null;
            $projects = null;
        } else {
            $tasks = null;
            $projects = Project::where('active',1)->get();
        } */
        $tasks = null;
        $projects = Project::where('active',1)->get();
     /*    $leave_types = $api->get_available_leave_types(); */

        return view('Centaur::afterhours.edit',['afterhour' => $afterhour,'employees' => $employees,'projects' => $projects,'tasks' => $tasks]);
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
            'ERP_leave_type' => isset($request['ERP_leave_type']) ? $request['ERP_leave_type'] : 3,
            'erp_task_id'    => isset($request['erp_task_id']) ? $request['erp_task_id'] : null,
            'employee_id'  	 => $request['employee_id'],
            'project_id'  	 => $request['project_id'],
            'date'    		 => $request['date'],
            'start_time'  	 => $request['start_time'],
            'end_time'  	 => $request['end_time'],
            'comment'  		 => $request['comment']
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
        $send_to = EmailingController::sendTo('afterhours', 'confirm');
        $approve_employee = Sentinel::getUser()->employee;
    
        $afterHour = Afterhour::find($request['id']);
        if( $afterHour ) {
            $employee = $afterHour->employee;
            $mail = $employee->email;
    
            $data = array(
                'approve'  		    =>  intval($request['approve']),
                'approve_h'  		=>  $request['approve'] == 0 ? '00:00:00' : $request['approve_h'],
                'approved_id'    	=>  $approve_employee ? $approve_employee->id : null,
                'approved_date'	    =>  date("Y-m-d")
            );
            
            $afterHour->updateAfterhour($data);

         /*    $api = new ApiController();
            $leave_types = $api->send_leave_request($afterHour, 'aft'); */
        
            Mail::to($mail)->send(new AfterHourApproveMail($afterHour));  
            Log::info("AfterHourApproveMail: " . implode(', ',array_unique($send_to)) );
      
            foreach(array_unique($send_to) as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new AfterHourApproveMail($afterHour));     
                }
            }

            $message = session()->flash('success', 'Zahtjev je potvrđen');
        } else {
            $message = session()->flash('error', 'Zahtjev nije nađen');
        }

        return redirect()->route('dashboard')->withFlashMessage($message);
    }

    public function storeConfMulti(Request $request)
    {
        $approve_employee = Sentinel::getUser()->employee;
        if( is_array($request['id']) ) {
            if( ! isset($request['approve']) ) {
				$message = 'Nemoguće spremiti, nije označeno ni jedno odobrenje.';
				return $message;
            } 

            $count = 0;
			foreach ($request['id'] as $key => $id) {
				if(isset($request['approve'][$key]) && $request['approve'][$key] != null && $request['approve'][$key] != '') {
                    if($request['type'][$key] == 'aft')  {
                        $send_to = EmailingController::sendTo('afterhours', 'confirm');
                        
                        $count++;
                        $afterHour = Afterhour::find( $id );
                        $data = array(
                            'approve'  		    => $request['approve'][$key],
                            'approve_h'  		=> $request['approve'][$key] == 0 ? '00:00:00' : $request['approve_h'][$key],
                            'approved_id'    	=> $approve_employee->id,
                            'approved_reason'  	=> $request['approved_reason'][$key],
                            'approved_date'	=> date("Y-m-d")
                        );
                    
                        $afterHour->updateAfterhour($data);
                        $employee = $afterHour->employee;
                        $mail = $employee->email;
                       
                        if( $mail) {
                            Mail::to( $mail )->send(new AfterHourApproveMail($afterHour)); 
                        }
                        Log::info("AfterHourApproveMail multi: " . implode(',', array_unique($send_to)));
		            
                        foreach(array_unique($send_to) as $send_to_mail) {
                            if( $send_to_mail != null & $send_to_mail != '' ) {
                                Mail::to($send_to_mail)->send(new AfterHourApproveMail($afterHour)); 
                            }
                        }  
                        
                    } elseif ($request['type'][$key] == 'abs') {
                        $send_to_abs = EmailingController::sendTo('absences','confirm');
                        $count++;
                        $absence = Absence::find( $id );
                
                        $data = array(
                            'approve'  		    => $request['approve'][$key],
                            'approved_id'    	=> $approve_employee->id,
                            'approved_reason'  	=> $request['approved_reason'][$key],
                            'approved_date'		=> date('Y-m-d')
                        );
                                
                        $absence->updateAbsence($data);

                        $employee_mail = $absence->employee->email;
                        array_push($send_to_abs, $employee_mail ); // mail zaposlenika

                        $firstSuperior = $absence->employee->work->firstSuperior; // prvi nadređeni
                        if($firstSuperior) {
                            $mail_firstSuperior = $firstSuperior->email;
                            array_push($send_to_abs, $mail_firstSuperior);
                        } else {
                            $manager = $absence->employee->work->employee; // voditelj odjela
                            $mail_manager = $manager->email;
                            array_push($send_to_abs, $mail_manager);
                        }

                        $send_to_abs = array_diff( $send_to_abs, array(	$approve_employee )); // bez djelatnika koji odobrava
                        Log::info("AbsenceConfirmMail multi: " . implode(',', array_unique( $send_to_abs )));
		            
                      //   try { 
                            foreach(array_unique( $send_to_abs ) as $send_to_mail) {
                                if( $send_to_mail != null & $send_to_mail != '' ) {
                                    Mail::to($send_to_mail)->send(new AbsenceConfirmMail($absence)); // mailovi upisani u mailing 
                                }
                            }
                      //  } catch (\Throwable $th) {
                        //    session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
                          //  return redirect()->back();
                        //} 
                    }
				}
            }
            $message = 'Uspješno je obrađeno ' . $count . ' zahtjeva!';
            return $message;
          /*   return redirect()->back()->withFlashMessage($message); */
        } else {
            return "Greška, nešto nije prošlo dobro";
        }
    }

    public function confirmation_show_after( $id)
	{
		$afterHour = Afterhour::find( $id);

        $time1 = new DateTime($afterHour->start_time );
		$time2 = new DateTime($afterHour->end_time );
		
		$interval = $time2->diff($time1);
        $interval = $interval->format('%H:%I');
        
		return view('Centaur::afterhours.confirmation_show_after',['afterhour_id'=> $id, 'afterHour' => $afterHour, 'interval' => $interval]);
    }
    
    public function storeConf_update( Request $request, $id )
    {
		$afterhour = Afterhour::find($id );
	
		$odobrio_user = Sentinel::getUser()->employee;
		
		$data = array(
			'approve'  			=>  $request['approve'],
            'approved_id'    	=>  $odobrio_user->id,
            'approve_h'  		=>  $request['approve'] == 0 ? '00:00:00' : $request['approve_h'],
			'approved_reason'  	=>  $request['approved_reason'],
			'approved_date'		=>  date('Y-m-d')
		);
				
		$afterhour->updateAfterhour($data);

        if($request['email'] == 1 ){ 
			$send_to = EmailingController::sendTo('afterhours', 'confirm');
			array_push($send_to, $afterhour->employee->email );
			try {
				foreach(array_unique($send_to) as $send_to_mail) {
					if( $send_to_mail != null & $send_to_mail != '' ) {
						Mail::to($send_to_mail)->send(new AfterHourApproveMail($afterhour)); // mailovi upisani u mailing 
					}
				}
			} catch (\Throwable $th) {
				session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
				return redirect()->back();
			}
		}
		
	    /* 	$message = session()->flash('success',  $afterhour->approve == 1 ? __('absence.approved') :  __('absence.refused') ); */
		/*  return redirect()->route('dashboard')->withFlashMessage($message); */
        /*  return redirect()->back()->withFlashMessage($message);*/

        $message = $afterhour->approve == 1 ?  __('absence.changed_approval') . ': ' . $request['approve_h'] . ' ' . __('absence.approved') :  __('absence.changed_approval') . ': ' .  __('absence.refused'); 
		return $message;
	}
}