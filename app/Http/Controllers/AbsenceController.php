<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AbsenceRequest;
use App\Http\Controllers\BasicAbsenceController;
use App\Http\Controllers\EmailingController;
use App\Models\Absence;
use App\Models\AbsenceType;
use App\Models\Employee;
use Sentinel;
use App\Mail\AbsenceMail;
use App\Mail\AbsenceUpdateMail;
use App\Mail\AbsenceConfirmMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Emailing;
use App\Models\Department;
use DateTime;

class AbsenceController extends BasicAbsenceController
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
    public function index(Request $request)
    {
        $empl = Sentinel::getUser()->employee;
		
		$permission_dep = array();
		$data_absence = array();
		$bolovanje = array();
		$docs = '';
		$ova_godina = date('Y');
		$prosla_godina = $ova_godina - 1;
		$type = null;

		if(isset($_GET['year']) && $_GET['year']) {
			$year = $_GET['year'];
		} else {
			$year = $ova_godina;
		}
		if(isset($_GET['type']) && $_GET['type']) {
			$type = AbsenceType::where( 'mark', $_GET['type'] )->first();
		} 

		if( Sentinel::inRole('administrator') ) {
			$absences = Absence::whereYear('start_date', $year)->get();
			$absences = $absences->merge(Absence::whereYear('end_date', $year)->get());
			
			

		} else {
			$absences = Absence::where('employee_id',$empl->id)->whereYear('start_date', $year)->orderBy('start_date','ASC')->get();
			$absences = $absences->merge(Absence::where('employee_id',$empl->id)->whereYear('end_date', $year)->orderBy('start_date','ASC')->get());
		
		}
		if($type) {
			$absences = $absences->where('type', $type->id);
		}
		if($empl) {
			$years = BasicAbsenceController::yearsRequests($empl); // sve godine zahtjeva
			arsort($years);

			$data_absence = array(
				'years'  		 => $years,  
				'years_service'  => BasicAbsenceController::yearsServiceCompany( $empl ),  
				'all_servise'  	 => BasicAbsenceController::yearsServiceAll( $empl ), 
				'days_OG'  		 => BasicAbsenceController::daysThisYear( $empl ), 
				'razmjeranGO'  	 => BasicAbsenceController::razmjeranGO( $empl ),  //razmjeran go ova godina
				'zahtjevi' 		 => BasicAbsenceController::requestAllYear( $empl ), 
				'bolovanje' 	 => BasicAbsenceController::bolovanje( $empl ), 
				'docs' 		 	 => DashboardController::profile_image( $empl->id ), 
				'user_name' 	 => DashboardController::user_name( $empl->id ), 

			);
			
			/* dd($data_absence); */
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		/* 	$data_absence = BasicAbsenceController::zahtjevi( $empl ); */
		} else {
			$empl = array();
		}
		
		return view('Centaur::absences.index', ['absences' => $absences, 'data_absence' => $data_absence, 'ova_godina' => $ova_godina,'prosla_godina' => $prosla_godina,'bolovanje' => $bolovanje, 'years' => $years, 'employee' => $empl, 'permission_dep' => $permission_dep, 'docs' => $docs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$type = '';

		if($request['type']) {
			$type = $request['type'];
		}
		
		$employees = Employee::where('id','<>',1)->where('checkout',null)->get();
		$absenceTypes = AbsenceType::get();
		
		$user = Sentinel::getUser();
		
		return view('Centaur::absences.create', ['employees' => $employees, 'type' => $type, 'absenceTypes' => $absenceTypes, 'user' => $user]);
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

	   if(isset($request['decree'])) {
		   if ($request['decree'] == 1) {
				$decree = 1;
		  	} else {
				$decree = 0;
		   }
		   
	   } else {
			$decree = 0;
	   }
	   if(is_array($request['employee_id'])  && count($request['employee_id']) > 0) {
		   	foreach($request['employee_id'] as $employee_id){
				$data = array(
					'type'  			=> $absenceType,
					'employee_id'  		=> $employee_id,
					'start_date'    	=> date("Y-m-d", strtotime($request['start_date'])),
					'end_date'			=> date("Y-m-d", strtotime($request['end_date'])),
					'start_time'  		=> $request['start_time'],
					'end_time'  		=> $request['end_time'],
					'comment'  			=> $request['comment'],
					'decree'  			=> $decree,
				);
				if(isset($request['decree']) && $request['decree'] == 1) {
					$data += ['approve'=>1];
					$data += ['approved_date'=>date('Y-m-d')];
					$data += ['approved_id'=>Sentinel::getUser()->employee['id']];
				}
				$absence = new Absence();
				$absence->saveAbsence($data);

				if($request['email'] == 'DA') {
					/* mail obavijest o novoj poruci */
					$send_to = EmailingController::sendTo('absences','confirm');

					if(isset($request['decree']) && $request['decree'] == 1 ) {
						array_push($send_to, $absence->employee->email );
					} 
					$send_to = array_merge($send_to, EmailingController::sendTo('absences','create') );
					try {
						foreach(array_unique($send_to) as $send_to_mail) {
							if( $send_to_mail != null & $send_to_mail != '' ) {
								Mail::to($send_to_mail)->send(new AbsenceMail($absence)); // mailovi upisani u mailing 
							}
						} 
					} catch (\Throwable $th) {
						session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
						return redirect()->back();
					}
					
			   } 
			}
	   } else {
			$data = array(
				'type'  			=> $absenceType,
				'employee_id'  		=> $request['employee_id'],
				'start_date'    	=> date("Y-m-d", strtotime($request['start_date'])),
				'end_date'			=> date("Y-m-d", strtotime($request['end_date'])),
				'start_time'  		=> $request['start_time'],
				'end_time'  		=> $request['end_time'],
				'comment'  			=> $request['comment'],
				'decree'  			=> $decree,
			);
			if(isset($request['decree']) && $request['decree'] == 1) {
				$data += ['approve'=>1];
				$data += ['approved_date'=>date('Y-m-d')];
				$data += ['approved_id'=>Sentinel::getUser()->employee['id']];
			}
			$absence = new Absence();
			$absence->saveAbsence($data);

			if($request['email'] == 'DA') {
				/* mail obavijest o novoj poruci */
				
				$send_to = EmailingController::sendTo('absences','confirm');
				$send_to = array_merge($send_to, EmailingController::sendTo('absences','create') );
				try {
					foreach(array_unique($send_to) as $send_to_mail) {
						if( $send_to_mail != null & $send_to_mail != '' ) {
							Mail::to($send_to_mail)->send(new AbsenceMail($absence)); // mailovi upisani u mailing 
						}
					}
				} catch (\Throwable $th) {
					session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
					return redirect()->back();
				}
		   } 
	   }
	 
	   	$message = session()->flash('success', __('ctrl.request_sent'));
		
		/* return redirect()->route('absences.index')->with('modal','true')->with('absence','true')->withFlashMessage($message); */
		return redirect()->back()->with('modal','true')->with('absence','true')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empl = Employee::find($id);

		if($empl) {
			$permission_dep = explode(',', $empl->work->department->departmentRole->toArray()[0]['permissions']);
			$absences = Absence::where('employee_id',$empl->id)->get();
		} else {
			 $permission_dep = array();
			 $empl = array();
			 $absences = array();
		}
		
		return view('Centaur::absences.show', ['absences' => $absences, 'permission_dep' => $permission_dep]);
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $absence = Absence::find($id);
		$employees = Employee::where('id','<>',1)->where('checkout',null)->get();
		$absenceTypes = AbsenceType::get();
		
		$user = Sentinel::getUser();
		
		return view('Centaur::absences.edit', ['absence' => $absence,'employees' => $employees, 'absenceTypes' => $absenceTypes, 'user' => $user]);
		
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
        $absence = Absence::find($id);
		$absenceType = AbsenceType::where('mark',$request['type'])->first()->id;
		if(isset($request['decree'])) {
			if ($request['decree'] == 1) {
					$decree = 1;
			   	} else {
					$decree = 0;
			}
			
		} else {
			$decree = 0;
		}
		
		$data = array(
			'type'  			=> $absenceType,
			'employee_id'  		=> $request['employee_id'],
			'start_date'    	=> date("Y-m-d", strtotime($request['start_date'])),
			'end_date'			=> date("Y-m-d", strtotime($request['end_date'])),
			'start_time'  		=> $request['start_time'],
			'end_time'  		=> $request['end_time'],
			'comment'  			=> $request['comment'],
			'decree'  			=> $decree,
		);
		if(isset($request['decree']) && $request['decree'] == 1) {
			$data += ['approve'=>1];
			$data += ['approved_date'=>date('Y-m-d')];
			$data += ['approved_id'=>Sentinel::getUser()->employee['id']];
		}
		$absence->updateAbsence($data);

		if($request['email'] == 'DA') {
			/* mail obavijest o novoj poruci */
			$send_to = EmailingController::sendTo('absences','confirm');

			if(isset($request['decree']) && $request['decree'] == 1 ) {
				array_push($send_to, $absence->employee->email );
			} 
			$send_to = array_merge($send_to, EmailingController::sendTo('absences','create') );
			try {
				foreach(array_unique($send_to) as $send_to_mail) {
					if( $send_to_mail != null & $send_to_mail != '' ) {
						Mail::to($send_to_mail)->send(new AbsenceUpdateMail($absence)); // mailovi upisani u mailing 
					}
				} 
			} catch (\Throwable $th) {
				session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
				return redirect()->back();
			}
			
	   } 

		session()->flash('success', __('ctrl.data_edit'));
		
		return redirect()->route('absences.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $absence = Absence::find($id);
		$absence->delete();
		
		$message = session()->flash('success',__('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
	}
	
	public function storeConf(Request $request)
    {
		$absence = Absence::find($request['id']);
		
		/* if($absence->approve != null ) {
			return view('Centaur::absences.confirmation_show',['absence' => $absence, 'absence_id' => $absence->id]);
		} */

		$odobrio_user = Sentinel::getUser()->employee;

		$datum = new DateTime('now');

		$data = array(
			'approve'  			=>  $_GET['approve'],
			'approved_id'    	=>  $odobrio_user->id,
			'approve_reason'  	=>  $_GET['approve_reason'],
			'approved_date'		=>  date_format($datum,'Y-m-d')
		);
				
		$absence->updateAbsence($data);

		/* mail obavijest o novoj poruci */
		$emailings = Emailing::get();
		

		$departments = Department::get();
		$employees = Employee::where('checkout',null)->get();

		$employee_mail = $absence->employee->email;
		
		$send_to = EmailingController::sendTo('absences','confirm');
		array_push($send_to, $employee_mail ); // mail zaposlenika

		try {
			foreach(array_unique($send_to) as $send_to_mail) {
				if( $send_to_mail != null & $send_to_mail != '' ) {
					Mail::to($send_to_mail)->send(new AbsenceConfirmMail($absence)); // mailovi upisani u mailing 
				}
			}
		} catch (\Throwable $th) {
			session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
			return redirect()->back();
		}
		
		
		
		$message = session()->flash('success', __('absence.approved'));

		return redirect()->route('dashboard')->withFlashMessage($message);
	}

	public function storeConf_update(Request $request)
    {
		$absence = Absence::find($request['id']);
	
		$odobrio_user = Sentinel::getUser()->employee;

		$datum = new DateTime('now');

		$data = array(
			'approve'  			=>  $_GET['approve'],
			'approved_id'    	=>  $odobrio_user->id,
			'approve_reason'  	=>  $_GET['approve_reason'],
			'approved_date'		=>  date_format($datum,'Y-m-d')
		);
				
		$absence->updateAbsence($data);

		if($request['email'] == 1 ){ 
			/* mail obavijest o novoj poruci */

			$send_to = EmailingController::sendTo('absences','confirm');
			array_push($send_to, $absence->employee->email );
			try {
				foreach(array_unique($send_to) as $send_to_mail) {
					if( $send_to_mail != null & $send_to_mail != '' ) {
						Mail::to($send_to_mail)->send(new AbsenceConfirmMail($absence)); // mailovi upisani u mailing 
					}
				}
			} catch (\Throwable $th) {
				session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
				return redirect()->back();
			}
			
		}
		
		$message = session()->flash('success',  $absence->approve == 1 ? __('absence.approved') :  __('absence.not_approved') );
		
		/* return redirect()->route('dashboard')->withFlashMessage($message); */
		return redirect()->back()->withFlashMessage($message);
	}

	public function confirmation_show(Request $request)
	{
		$absence = Absence::find( $request['absence_id']);

		return view('Centaur::absences.confirmation_show',['absence_id'=> $request['absence_id'], 'absence' => $absence]);
	}

	public static function countRequest ()  
	{
		$employee_id = Sentinel::getUser()->employee->id;
		$sent_to_empl = array();
		
		$emailings_absence = Emailing::join('tables','tables.id','emailings.model')->select('emailings.*', 'tables.name')->where('tables.name', 'absences')->where('emailings.method', 'create')->first();
		$absences_count = Absence::where('approve',null)->get()->count();
		if($emailings_absence) {
			$sent_to_empl = explode(',', $emailings_absence->sent_to_empl );
		}
		
		if( in_array($employee_id, $sent_to_empl)) {
			$count = $absences_count;
		} else {
			$count = 0;
		}

		return $count;
	}

	public static function dateDifference($date_1 , $date_2 , $differenceFormat = '%h:%i' )
	{
		$datetime1 = date_create($date_1);
		$datetime2 = date_create($date_2);
	
		$interval = date_diff($datetime1, $datetime2);
	
		return $interval->format($differenceFormat);
	}	
}