<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KeyResult;
use App\Models\KeyResultTask;
use App\Models\Employee;
use App\Mail\KeyResultTaskProgressMail;
use App\Mail\KeyResultTaskMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;
use Sentinel;
use Log;

class KeyResultTaskController extends Controller
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
        $this->test_mail = false;  // true - test na jelena.juras@duplco.hr
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
    public function create(Request $request)
    {
        if(isset( $request['keyResults_id']) ) {
            $keyResult = KeyResult::find($request['keyResults_id']);
            $keyResults_id =  $keyResult->id;
            $keyResults = KeyResult::where('okr_id',  $keyResult->okr_id)->get();
        } else {
            $keyResult = null;
            $keyResults_id = null;
            $keyResults = KeyResult::get();
        }
       
        $employees = Employee::employees_lastNameASCStatus(1);

        return view('Centaur::key_result_tasks.create', ['keyResults' => $keyResults,'employees' => $employees, 'keyResults_id' => $keyResults_id, 'this_keyResult' => $keyResult]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( is_array($request['employee_id'])) {
            foreach ( $request['employee_id'] as $employee_id) {
                $data = array(
                    'employee_id'  	=> $employee_id,
                    'keyresult_id'  => $request['keyresult_id'],
                    'name'  	    => $request['name'],
                    'comment'  		=> $request['comment'],
                    'end_date'  	=> $request['end_date'],
                    'progresss'  	=> $request['progresss'] ? $request['progresss'] : '',
                );
                
                $keyResultTask = new KeyResultTask();
                $keyResultTask->saveKeyResultTask($data);
        
                if( $keyResultTask->employee ) {
                    if( $this->test_mail ) {
                        $send_to = 'jelena.juras@duplico.hr'; 
                    } else { 
                        $send_to = $keyResultTask->employee->email;
                    }
         
                     if( $send_to &&  $send_to != '' &&  $send_to != null ) {
                         try {   
                            Mail::to($send_to)->send(new KeyResultTaskMail($keyResultTask));
         
                         } catch (\Throwable $th) {
                             $email = 'jelena.juras@duplico.hr';
                             $url = $_SERVER['REQUEST_URI'];
                             Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
                 
                             $message = session()->flash('success',  __('emailing.not_send'));
                             return redirect()->back()->withFlashMessage($message);
                         }
                         
                     }
                }
            }
        } else {
            $data = array(
                'employee_id'  	=> null,
                'keyresult_id'  => $request['keyresult_id'],
                'name'  	    => $request['name'],
                'comment'  		=> $request['comment'],
                'end_date'  	=> $request['end_date'],
                'progresss'  	=> $request['progresss'] ? $request['progresss'] : '',
            );
            
            $keyResultTask = new KeyResultTask();
            $keyResultTask->saveKeyResultTask($data);
        }
       
        
        return 'task_' . $keyResultTask->id;

      /*   session()->flash('success',  __('ctrl.data_save'));
		
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
        $keyResultTask = KeyResultTask::find($id);
        $employee_ids = KeyResultTask::where('name', $keyResultTask->name )->get()->pluck('employee_id')->toArray();
      
        $employees = Employee::employees_lastNameASCStatus(1);
        $keyResults = KeyResult::get();

        return view('Centaur::key_result_tasks.edit', ['keyResults' => $keyResults,'employees' => $employees, 'employee_ids' => $employee_ids,'keyResultTask' => $keyResultTask]);
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
        $keyResultTask = KeyResultTask::find($id);
        $keyResultTasks = KeyResultTask::where('keyresult_id', $keyResultTask->keyresult_id )->where('name', $keyResultTask->name )->get();
        $ids = $keyResultTasks->pluck('employee_id');
        if(  $request['employee_id'] && count( $request['employee_id'] )  > 0) {
            foreach ( $request['employee_id'] as $employee_id) {
                $task = $keyResultTasks->where('employee_id', $employee_id)->first();
    
                $data = array(
                    'keyresult_id'  => $request['keyresult_id'],
                    'employee_id'  	=> $employee_id,
                    'name'  	    => $request['name'],
                    'comment'  		=> $request['comment'],
                    'end_date'  	=> $request['end_date'],
                    'progresss'  	=> $request['progresss'] ? $request['progresss'] : '',
                );
    
                if ( $task ) {
                    $task->updateKeyResultTask($data);
                } else {
                    $task = new KeyResultTask();
                    $task->saveKeyResultTask($data);
                }
            }
        } else {
            $data = array(
                'keyresult_id'  => $request['keyresult_id'],
                'employee_id'  	=> null,
                'name'  	    => $request['name'],
                'comment'  		=> $request['comment'],
                'end_date'  	=> $request['end_date'],
                'progresss'  	=> $request['progresss'] ? $request['progresss'] : '',
            );
            $keyResultTask->updateKeyResultTask($data);
        }
        if ( $ids &&  $request['employee_id']  ) {
            foreach ($ids as $id) {
                if ( ! in_array( $id, $request['employee_id'] )) {
                  $task = $keyResultTasks->where('employee_id', $id )->first();
                  $task->delete();
                }
              }
        }
       

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
        $keyResultTask = KeyResultTask::find($id);
        $keyResultTask->delete();
        
        session()->flash('success',  __('ctrl.data_delete'));
		
        return redirect()->back();
    }

    public function progressTask ( Request $request ) 
    {
        $keyResultTask = KeyResultTask::find($request['id']);
       
        $data = array(
			'progress'  	=> $request['progress'],
		);
			
		$keyResultTask->updateKeyResultTask($data);
       
        $send_to = array();
        if( $this->test_mail ) {
            $send_to = array('jelena.juras@duplico.hr');
        } else { 
            if(Sentinel::getUser()->employee->id != $keyResultTask->keyResult->employee_id ) {
                array_push( $send_to, $keyResultTask->keyResult->employee->email);
            } 
        }
        Log::info($send_to);
        if( !empty($send_to)) {
            try {   
                foreach (array_unique($send_to) as $mail) {
                    if( $mail != '' && $mail != null) {
                        Mail::to($mail)->send(new KeyResultTaskProgressMail ($keyResultTask));
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

        return "Uspješno spremljeno";
    }
}
