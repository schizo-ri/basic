<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Okr;
use App\Models\KeyResult;
use App\Models\KeyResultTask;
use App\Models\Employee;
use App\Models\AnnualGoal;
use App\Mail\OkrProgressMail;
use App\Mail\OkrMail;
use App\Mail\KeyResultReminderMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;
use Sentinel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OkrExport;
use Log;

class OkrController extends Controller
{
    private $test_mail;

    /**
	*
	* Set middleware to quard controller.
	* @return void
	*/
    public function __construct()
    {
        $this->middleware('sentinel.auth');
        $this->test_mail = true;  // true - test na jelena.juras@duplco.hr
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employee_okrs = null;
        $employee_key_results = null;
        $employee_key_result_tasks = null;
        $annualGoals = AnnualGoal::where('year', date('Y'))->get();
        $employees = Okr::allEmployeeOnOKRs();

        if( isset($request['status'] ) ) {
            if( $request['status'] == 'finished' ) {
                $okrs = Okr::where('progress', 100)->with('hasKeyResults')->get();
                $keyResults = KeyResult::where('progress', 100)->get();
                $keyResultTasks = KeyResultTask::where('progress', 100)->get();
            } else if($request['status'] == 'unfinished' ) {
                $okrs =  Okr::where('progress', '<>', 100)->with('hasKeyResults')->get();
                $keyResults = KeyResult::where('progress', '<>', 100)->get();
                $keyResultTasks = KeyResultTask::where('progress', '<>', 100)->get();
            } else if($request['status'] == 'all' ) {
                $okrs = Okr::with('hasKeyResults')->get();
                $keyResults = KeyResult::get();
                $keyResultTasks = KeyResultTask::get();
            }
        } else {
            $okrs = Okr::where('progress', '<>', 100)->with('hasKeyResults')->get();
            $keyResults = KeyResult::get();
            $keyResultTasks = KeyResultTask::get();
        }
        
        if( isset($request['tim'] ) ) {
            if( $request['tim'] == 'tim' ) {
                $okrs = $okrs->where('status', 1);
            }
            if( $request['tim'] == 'duplico' ) {
                $okrs = $okrs->where('status', 0);
            }
        }
        if( $okrs ) {
            
        }
        $unique_dates =  $okrs->pluck('start_date')->unique()->toArray();
        
        $quarters = array();
        foreach ($unique_dates as $date) {
            array_push($quarters,  'Q'.ceil(date("n", strtotime(date($date))) / 3) .' - '. date("Y", strtotime(date($date)))  );
        }
        $this_quarter = 'Q'.ceil(date("n", strtotime(date('Y-m-d'))) / 3) .' - '. date("Y", strtotime(date('Y-m-d'))) ;

        $user = Sentinel::getUser();
        $employee = $user->employee;
        if( $employee ) {
            $employee_okrs = $okrs->where('employee_id', $employee->id );
            if( ! isset($request['status'] ) ||  $request['status'] == 'all' ) {
                $employee_key_results = KeyResult::where('employee_id', $employee->id )->get();
                $employee_key_result_tasks = KeyResultTask::where('employee_id', $employee->id )->get();
            } else {
                if( $request['status'] == 'finished' ) {
                    $employee_key_results = KeyResult::where('employee_id', $employee->id )->where('progress', 100)->get();
                    $employee_key_result_tasks = KeyResultTask::where('employee_id', $employee->id )->get();
                } else if($request['status'] == 'unfinished' ) {
                    $employee_key_results = KeyResult::where('employee_id', $employee->id )->where('progress', '<>', 100)->get();
                    $employee_key_result_tasks = KeyResultTask::where('employee_id', $employee->id )->get();
                } 
            }
        } 

        return view('Centaur::okrs.index', ['okrs' => $okrs,'all_keyResults' => $keyResults,'all_keyResultTasks' => $keyResultTasks,'employees' => $employees,'this_quarter' => $this_quarter,'annualGoals' => $annualGoals,'quarters' => $quarters,'employee' => $employee,'employee_okrs' => $employee_okrs,'employee_key_results' => $employee_key_results,'employee_key_result_tasks' => $employee_key_result_tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::employees_lastNameASCStatus(1);

        return view('Centaur::okrs.create', ['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $year = $request['year'];

        switch ($request['quarter']) {
            case 'q1':
                $start_date = $year .  '-01-01';
                $end_date = $year .  '-03-31';
                break;
            case 'q2':
                $start_date = $year .  '-04-01';
                $end_date = $year .  '-06-30';
                break;
            case 'q3':
                $start_date = $year .  '-07-01';
                $end_date = $year .  '-09-30';
                break;
            case 'q4':
                $start_date = $year .  '-10-01';
                $end_date = $year .  '-12-31';
                break;
        }
    
        $data = array(
			'employee_id'  	=> $request['employee_id'],
			'name'  	    => $request['name'],
			'comment'  		=> $request['comment'],
			'status'  		=> $request['status'],
			'start_date'  	=> $start_date,
			'end_date'  	=> $end_date,
			'progresss'  	=> $request['progresss'],
		);
			
		$okr = new Okr();
		$okr->saveOkr($data);

        if( $okr->employee ) {
            if( $this->test_mail ) {
                $send_to = 'jelena.juras@duplico.hr';
            } else { 
                $send_to = $okr->employee->email;
            }
            Log::info( $send_to);
            if( $send_to &&  $send_to != '' &&  $send_to != null ) {
                try {   
                    Mail::to($send_to)->send(new OkrMail($okr));

                } catch (\Throwable $th) {
                    $email = 'jelena.juras@duplico.hr';
                    $url = $_SERVER['REQUEST_URI'];
                    Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
        
                    $message = session()->flash('success',  __('emailing.not_send'));
                    return redirect()->back()->withFlashMessage($message);
                }
                
            }
        }       

        return 'okr_' . $okr->id;
        /* session()->flash('success',  __('ctrl.data_save'));
		
        return redirect()->back(); */
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
        $okr = Okr::find($id);

        $employees = Employee::employees_lastNameASCStatus(1);

        return view('Centaur::okrs.edit', ['employees' => $employees,'okr' => $okr]);
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
    
        $okr = Okr::find($id);

        $year = $request['year'];

        switch ($request['quarter']) {
            case 'q1':
                $start_date = $year .  '-01-01';
                $end_date = $year .  '-03-31';
                break;
            case 'q2':
                $start_date = $year .  '-04-01';
                $end_date = $year .  '-06-30';
                break;
            case 'q3':
                $start_date = $year .  '-07-01';
                $end_date = $year .  '-09-30';
                break;
            case 'q4':
                $start_date = $year .  '-10-01';
                $end_date = $year .  '-12-31';
                break;
        }

        $data = array(
			'employee_id'  	=> $request['employee_id'],
			'name'  	    => $request['name'],
			'comment'  		=> $request['comment'],
			'status'  		=> $request['status'],
			'start_date'  	=> $start_date,
			'end_date'  	=> $end_date,
			'progresss'  	=> $request['progresss'],
		);
			
		$okr->updateOkr($data);

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
        $okr = Okr::find($id);

        $keyResults = $okr->hasKeyResults;

        if($keyResults && count($keyResults) > 0) {
            foreach ($keyResults as $keyResult) {
                $tasks =  $keyResult->hasTasks;
                if( count( $tasks ) > 0) {
                    foreach ($tasks as $task) {
                        $task->delete();
                    }
                }
                $keyResult->delete();
            }
        }
       
        $okr->delete();

        session()->flash('success',  __('ctrl.data_delete'));
		
        return redirect()->back();

    }

    public function progressOkr ( Request $request ) 
    {
        $okr = Okr::find( $request['id'] );
        
        $data = array(
			'progress'  	=> $request['progress'],
		);
			
		$okr->updateOkr($data);

        if( $request['progress'] == 100) {
            $keyResults = $okr->hasKeyResults;
            if ( $keyResults && count($keyResults) >0 ) {
                foreach ($keyResults as $keyResult) {
                    $data = array(
                        'progress'  	=> $request['progress'],
                    );
                        
                    $keyResult->updateKeyResult($data);

                    $keyResultTasks = $keyResult->hasTasks;

                    if ( $keyResultTasks && count($keyResultTasks) >0 ) {
                        foreach ($keyResultTasks as $keyResultTask) {
                            $data = array(
                                'progress'  	=> $request['progress'],
                            );
                            $keyResultTask->updateKeyResultTask($data);
                        }
                    }
                }
            }
        }

        if(Sentinel::getUser()->employee->id != $okr->employee_id ) {
            if( $this->test_mail ) {
                $send_to = array('jelena.juras@duplico.hr');
            } else { 
                if($okr->employee) {
                    $send_to = array( $okr->employee->email );
                } else {
                    $send_to = array();
                }
                
            }
            Log::info($send_to);
            if( !empty($send_to)) {
                try {   
                    foreach (array_unique($send_to) as $mail) {
                        if( $mail != '' && $mail != null) {
                            Mail::to($mail)->send(new OkrProgressMail($okr));
                        }
                    }   
                } catch (\Throwable $th) {
                    $email = 'jelena.juras@duplico.hr';
                    $url = $_SERVER['REQUEST_URI'];
                    Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
        
                    $message = session()->flash('success',  __('emailing.not_send'));
                    return redirect()->back()->withFlashMessage($message);
                }
            }
        }
        return "Uspješno spremljeno";
    }

    public function exportOkr( Request $request )
    {
        if( $request['employee_id'] == '*') {
            $okrs = Okr::with('hasKeyResults')->get();
        } else {
            $employee = Employee::with('hasOkrs')->with('hasKeyResult')->with('hasKeyResultTask')->find($request['employee_id']);
            $okrs = $employee->hasOkrs;
            $keyResults = $employee->hasKeyResult;
            $keyResultTasks = $employee->hasKeyResultTask;
        }
        
        $okrs_arr = array();
        $okr_arr = array();
        foreach ($okrs as $okr) {
            array_push($okr_arr, 'OKR');
            array_push($okr_arr, $okr->name);
            array_push($okr_arr, $okr->comment ? $okr->comment : '');
            array_push($okr_arr, ('Q'.ceil(date("n", strtotime(date($okr->start_date))) / 3) .' - '. date("Y", strtotime(date($okr->start_date)))));
            array_push($okr_arr, $okr->employee ? $okr->employee->user->first_name . ' ' . $okr->employee->user->last_name : '');
            array_push($okr_arr, $okr->status == 0 ? 'Duplico OKR' : 'Timski OKR' );

            array_push($okrs_arr, $okr_arr);
            $okr_arr = array();
            if( count($okr->hasKeyResults) > 0 ) {
                foreach ($okr->hasKeyResults as $keyResult) {
                    array_push($okr_arr, 'Key result');
                    array_push($okr_arr, $keyResult->name);
                    array_push($okr_arr, $keyResult->comment);
                    array_push($okr_arr, ('Q'.ceil(date("n", strtotime(date($keyResult->end_date))) / 3) .' - '. date("Y", strtotime(date($keyResult->end_date)))));
                    array_push($okr_arr, $keyResult->employee ? $keyResult->employee->user->first_name . ' ' . $keyResult->employee->user->last_name : '' );
                    array_push($okr_arr, '' );

                    array_push($okrs_arr, $okr_arr);
                    $okr_arr = array();
                    
                    if( count($keyResult->hasTasks) > 0 ) {
                        foreach ($keyResult->hasTasks as $tasks) {
                            array_push($okr_arr, 'Task');
                            array_push($okr_arr, $tasks->name);
                            array_push($okr_arr, $tasks->comment);
                            array_push($okr_arr, ('Q'.ceil(date("n", strtotime(date($tasks->end_date))) / 3) .' - '. date("Y", strtotime(date($tasks->end_date)))));
                            array_push($okr_arr, $tasks->employee ? $tasks->employee->user->first_name . ' ' . $tasks->employee->user->last_name : '' );
                            array_push($okr_arr, '' );

                            array_push($okrs_arr, $okr_arr);
                            $okr_arr = array();
                        }
                    }
                }
            }
        }

        $export = new OkrExport($okrs_arr);
        
        return Excel::download($export, 'okr.xlsx');
    }

    public function reminderOkr ( Request $request ) 
    {
        $okr = Okr::find( $request['okr_id'] );
     
        foreach ($okr->hasKeyResults as $keyResult) {
            if( $keyResult->employee ) {
                if( $this->test_mail ) {
                    $employee_mail = 'jelena.juras@duplico.hr';
                } else { 
                    $employee_mail = $keyResult->employee->email;
                }
               
                Log::info("reminder Okr " .  $employee_mail);
            
                Mail::to($employee_mail)->send(new KeyResultReminderMail( $keyResult ));
            }
        }
       
        return "Podsjetnik je poslan";
    }
}
