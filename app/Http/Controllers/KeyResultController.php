<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KeyResult;
use App\Models\Okr;
use App\Models\Employee;
use App\Mail\KeyResultProgressMail;
use App\Mail\KeyResultMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;
use Sentinel;
use Log;

class KeyResultController extends Controller
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
        if(isset( $request['okr_id']) ) {
            $okr = Okr::find($request['okr_id']);
            $okr_id =  $okr->id;
        } else {
            $okr = null;
            $okr_id = null;
        }
       
        $employees = Employee::employees_lastNameASCStatus(1);
        $okrs = Okr::get();

        return view('Centaur::key_results.create', ['okrs' => $okrs,'employees' => $employees, 'okr_id' => $okr_id, 'this_okr' => $okr]);
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
			'employee_id'  	=> $request['employee_id'],
			'okr_id'  	    => $request['okr_id'],
			'name'  	    => $request['name'],
			'comment'  		=> $request['comment'],
			'end_date'  	=> $request['end_date'],
			'progresss'  	=> $request['progresss'],
		);
			
		$keyResult = new KeyResult();
		$keyResult->saveKeyResult($data);

        if( $keyResult->employee ) {
            if( $this->test_mail ) {
                $send_to = 'jelena.juras@duplico.hr';
            } else { 
                $send_to = $keyResult->employee->email;
            }

            if( $send_to &&  $send_to != '' &&  $send_to != null ) {
                try {   
                    Mail::to($send_to)->send(new KeyResultMail($keyResult));

                } catch (\Throwable $th) {
                    $email = 'jelena.juras@duplico.hr';
                    $url = $_SERVER['REQUEST_URI'];
                    Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
        
                    $message = session()->flash('success',  __('emailing.not_send'));
                    return redirect()->back()->withFlashMessage($message);
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
        $key_result = KeyResult::find($id);
       
        $employees = Employee::employees_lastNameASCStatus(1);
        /* $okrs = Okr::get(); */

        return view('Centaur::key_results.edit', ['key_result' => $key_result,/* 'okrs' => $okrs, */'employees' => $employees]);
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
        $keyResult = KeyResult::find($id);

        $data = array(
			'employee_id'  	=> $request['employee_id'],
			'okr_id'  	    => $request['okr_id'],
			'name'  	    => $request['name'],
			'comment'  		=> $request['comment'],
			'end_date'  	=> $request['end_date'],
			'progresss'  	=> $request['progresss'],
		);
			
		$keyResult->updateKeyResult($data);

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
        $keyResult = KeyResult::find($id);
       
        if($keyResult) {
            $tasks =  $keyResult->hasTasks;
            if( count( $tasks ) > 0) {
                foreach ($tasks as $task) {
                    $task->delete();
                }
            }
        }
      
        $keyResult->delete();

        session()->flash('success',  __('ctrl.data_delete'));
		
        return redirect()->back();
    }

    public function progressKeyResult ( Request $request ) 
    {
        $keyResult = KeyResult::find($request['id']);
        
        $data = array(
			'progress'  	=> $request['progress'],
		);
			
		$keyResult->updateKeyResult($data);

        if( $request['progress'] == 100) {
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

        if ( $keyResult->okr->status == 0 ) {
            if(Sentinel::getUser()->employee->id != $keyResult->okr->employee_id ) {
                if( $this->test_mail ) {
                    $send_to = array('jelena.juras@duplico.hr');
                } else { 
                    $send_to = array( $keyResult->okr->employee->email );
                }
                Log::info($send_to);
                if( ! empty($send_to)) {
                    try {   
                        foreach (array_unique($send_to) as $mail) {
                            if( $mail != '' && $mail != null) {
                                Mail::to($mail)->send(new KeyResultProgressMail($keyResult));
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
        }

        return "Uspješno spremljeno";
    }
}
