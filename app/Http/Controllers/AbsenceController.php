<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AbsenceRequest;
use App\Http\Controllers\BasicAbsenceController;
use App\Models\Absence;
use App\Models\AbsenceType;
use App\Models\Employee;
use Sentinel;
use App\Mail\AbsenceMail;
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

		if(isset($_GET['year']) && $_GET['year']) {
			$year = $_GET['year'];
		} else {
			$year = $ova_godina;
		}
		
		$years = BasicAbsenceController::yearsRequests($empl); // sve godine zahtjeva
		if(! in_array($ova_godina, $years)) {
			array_push($years, $ova_godina );
		}
		arsort($years);

		if( Sentinel::inRole('administrator') ) {
			$absences = Absence::whereYear('start_date', $year)->get();
			$absences = $absences->merge(Absence::whereYear('end_date', $year)->get());
		} else {
			$absences = Absence::where('employee_id',$empl->id)->whereYear('start_date', $year)->orderBy('start_date','ASC')->get();
			$absences = $absences->merge(Absence::where('employee_id',$empl->id)->whereYear('end_date', $year)->orderBy('start_date','ASC')->get());
		}

		if(isset($_GET['type']) && $_GET['type']) {
			$type = AbsenceType::where( 'mark', $_GET['type'] )->first();
			$absences = $absences->where('type', $type->id);
		}
		
		if($empl) {
			$data_absence = array(
				'years_service'  => BasicAbsenceController::yearsServiceCompany( $empl ),  
				'all_servise'  	=> BasicAbsenceController::yearsServiceAll( $empl ), 
				'days_OG'  		=> BasicAbsenceController::daysThisYear( $empl ), 
				'razmjeranGO'  	=> BasicAbsenceController::razmjeranGO( $empl ),  //razmjeran go ova godina
			//	'razmjeranGO_PG' => BasicAbsenceController::razmjeranGO_PG( $empl ), 
				'zahtjevi' 		 => BasicAbsenceController::requestAllYear( $empl ), 
			);
			$bolovanje = BasicAbsenceController::bolovanje( $empl );
			$docs = DashboardController::profile_image($empl);
		
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
				);

				$absence = new Absence();
				$absence->saveAbsence($data);
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
			);
			$absence = new Absence();
			$absence->saveAbsence($data);

			if($request['email'] == 'DA') {
				/* mail obavijest o novoj poruci */
				$emailings = Emailing::get();
				$send_to = array();
				$departments = Department::get();
				$employees = Employee::where('id','<>',1)->where('checkout',null)->get();
	
				if(isset($emailings)) {
					foreach($emailings as $emailing) {
						if($emailing->table['name'] == 'absences' && $emailing->method == 'create') {
							if($emailing->sent_to_dep) {
								foreach(explode(",", $emailing->sent_to_dep) as $prima_dep) {
									array_push($send_to, $departments->where('id', $prima_dep)->first()->email );
								}
							}
							if($emailing->sent_to_empl) {
								foreach(explode(",", $emailing->sent_to_empl) as $prima_empl) {
									array_push($send_to, $employees->where('id', $prima_empl)->first()->email );
								}
							}
						}
					}
				}
	
				foreach(array_unique($send_to) as $send_to_mail) {
					if( $send_to_mail != null & $send_to_mail != '' )
					Mail::to($send_to_mail)->send(new AbsenceMail($absence)); // mailovi upisani u mailing 
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
		
		$data = array(
			'type'  			=> $absenceType,
			'employee_id'  		=> $request['employee_id'],
			'start_date'    	=> date("Y-m-d", strtotime($request['start_date'])),
			'end_date'			=> date("Y-m-d", strtotime($request['end_date'])),
			'start_time'  		=> $request['start_time'],
			'end_time'  		=> $request['end_time'],
			'comment'  			=> $request['comment'],
		);
		
		$absence->updateAbsence($data);

		if($request['email'] == 'DA') {
			/* mail obavijest o novoj poruci */
			$emailings = Emailing::get();
			$send_to = array();
			$departments = Department::get();
			$employees = Employee::where('id','<>',1)->where('checkout',null)->get();

			if(isset($emailings)) {
				foreach($emailings as $emailing) {
					if($emailing->table['name'] == 'absences' && $emailing->method == 'create') {
						if($emailing->sent_to_dep) {
							foreach(explode(",", $emailing->sent_to_dep) as $prima_dep) {
								array_push($send_to, $departments->where('id', $prima_dep)->first()->email );
							}
						}
						if($emailing->sent_to_empl) {
							foreach(explode(",", $emailing->sent_to_empl) as $prima_empl) {
								array_push($send_to, $employees->where('id', $prima_empl)->first()->email );
							}
						}
					}
				}
			}

			foreach(array_unique($send_to) as $send_to_mail) {
				if( $send_to_mail != null & $send_to_mail != '' )
				Mail::to($send_to_mail)->send(new AbsenceMail($absence)); // mailovi upisani u mailing 
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
		
		if($request['email'] == 1 ){ 
			/* mail obavijest o novoj poruci */
			$emailings = Emailing::get();
			$send_to = array();
			$departments = Department::get();
			$employees = Employee::where('id','<>',1)->where('checkout',null)->get();

			$employee_mail = $absence->employee->email;
			array_push($send_to, $employee_mail ); // mail zaposlenika

			if(isset($emailings)) {
				foreach($emailings as $emailing) {
					if($emailing->table['name'] == 'absences' && $emailing->method == 'confirm') {
						if($emailing->sent_to_dep) {
							foreach(explode(",", $emailing->sent_to_dep) as $prima_dep) {
								array_push($send_to, $departments->where('id', $prima_dep)->first()->email );
							}
						}
						if($emailing->sent_to_empl) {
							foreach(explode(",", $emailing->sent_to_empl) as $prima_empl) {
								array_push($send_to, $employees->where('id', $prima_empl)->first()->email );
							}
						}
					}
				}
			}

			foreach(array_unique($send_to) as $send_to_mail) {
				if( $send_to_mail != null & $send_to_mail != '' )
				Mail::to($send_to_mail)->send(new AbsenceConfirmMail($absence)); // mailovi upisani u mailing 
			}
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
			$emailings = Emailing::get();
			$send_to = array();
			$departments = Department::get();
			$employees = Employee::where('id','<>',1)->where('checkout',null)->get();
			
			$employee_mail = $absence->employee->email;
			array_push($send_to, $employee_mail ); // mail zaposlenika
			
			if(isset($emailings)) {
				foreach($emailings as $emailing) {
					if($emailing->table['name'] == 'absences' && $emailing->method == 'confirm') {
						if($emailing->sent_to_dep) {
							foreach(explode(",", $emailing->sent_to_dep) as $prima_dep) {
								array_push($send_to, $departments->where('id', $prima_dep)->first()->email );
							}
						}
						if($emailing->sent_to_empl) {
							foreach(explode(",", $emailing->sent_to_empl) as $prima_empl) {
								array_push($send_to, $employees->where('id', $prima_empl)->first()->email );
							}
						}
					}
				}
			}

			foreach(array_unique($send_to) as $send_to_mail) {
				if( $send_to_mail != null & $send_to_mail != '' )
				Mail::to($send_to_mail)->send(new AbsenceConfirmMail($absence)); // mailovi upisani u mailing 
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

	
}
