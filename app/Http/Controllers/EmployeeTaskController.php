<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\EmployeeTask;
use App\Mail\TaskConfirmMail;
use App\Mail\TaskConfirmMail2;
use Illuminate\Support\Facades\Mail;
use Sentinel;
use Log;

class EmployeeTaskController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if( Sentinel::check()) {
            $employee = Sentinel::getUser()->employee;

            if(Sentinel::inRole('administrator')) {
                $task = Task::find($id);
                $employeeTasks = $task->employeeTasks->sortBy('created_at');
            } else {
                $employeeTasks = EmployeeTask::where('employee_id', $employee->id )->get();
            }

            return view('Centaur::employee_tasks.show', ['employeeTasks'=>$employeeTasks]);
        } else {
            session()->flash('error', 'Nisi prijavljen u program. Prijavi se i pokušaj ponovno' );
		
		    return redirect()->back();
        }
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

    public function tasks_confirm(Request $request)
    {
        $employee_task = EmployeeTask::find($request['id']);

        if( $employee_task->status  == 0  ) {
            $status = 1;
            $comment =  $request['comment'];
            $message = 'Zadatak je potvrđen';
        } else {
            $status = 0;
            $comment =  'Potvrda poništena! |' .$request['comment'];
            $message = 'Potvrda zadatka je poništena';
        }

        $data = array(
            'status'  	    => $status,
            'comment'  	    => $comment
        );

        $employee_task->updateEmployeeTask($data);

        $email_1 = $employee_task->task->employee->email;  // djelatnik koji je zadao zadatak
        $email_2 = $employee_task->employee->email;  // djelatnik koji je izvršio zadatak

        Mail::to($email_1)->send(new TaskConfirmMail($employee_task));
        Mail::to($email_2)->send(new TaskConfirmMail2($employee_task));

        session()->flash('success', $message );
		
		return redirect()->route('dashboard');
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
        $employee_task = EmployeeTask::find($id);
       
        if( $employee_task->status  == 0  ) {
            $status = 1;
            $comment =  $request['comment'];
            $message = 'Zadatak je potvrđen';
        } else {
            $status = 0;
            $comment =  'Potvrda poništena! |' .$request['comment'];
            $message = 'Potvrda zadatka je poništena';
        } 
        
        $data = array(
            'status'  	    => $status,
            'comment'  	    => $comment
        );
   
		$employee_task->updateEmployeeTask($data);

        $email_1 = $employee_task->task->employee->email;  // djelatnik koji je zadao zadatak
        $email_2 = $employee_task->employee->email;  // djelatnik koji je izvršio zadatak

        Mail::to($email_1)->send(new TaskConfirmMail($employee_task));
        Mail::to($email_2)->send(new TaskConfirmMail2($employee_task));

		session()->flash('success',   $message );
		
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
        //
    }
}
