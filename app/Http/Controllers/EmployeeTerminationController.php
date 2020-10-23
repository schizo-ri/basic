<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\EmployeeTermination;
use App\Models\Employee;
use App\Models\Termination;
use Sentinel;
use Illuminate\Support\Facades\Hash;
use App\Mail\EmployeeTerminationMail;
use Illuminate\Support\Facades\Mail;

class EmployeeTerminationController extends Controller
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
        $employee_terminations = EmployeeTermination::get();
        $empl = Sentinel::getUser()->employee;
        $permission_dep = DashboardController::getDepartmentPermission();

		return view('Centaur::employee_terminations.index', ['employee_terminations' => $employee_terminations, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::employees_lastNameASC();
        $terminations = Termination::orderBy('name','ASC')->get();

        return view('Centaur::employee_terminations.create', ['employees' => $employees,'terminations' => $terminations]);
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
			'employee_id'       => $request['employee_id'],
			'termination_id'    => $request['termination_id'],
			'notice_period'     => $request['notice_period'],
			'check_out_date'    => $request['check_out_date'],
			'comment'  	        => $request['comment'],
		);
     
		$employeeTermination = new EmployeeTermination();
		$employeeTermination->saveEmployeeTermination($data);
        
        $data_empl = array(
			'termination_id'    => $employeeTermination->id,
			'checkout'          => $employeeTermination->check_out_date
        );
        $employee = $employeeTermination->employee;
        $employee->updateEmployee($data_empl);
              
        // za odjavljenog djelatnika - korisnički kodaci deaktivirani
        
        $user = Sentinel::findById( $employee->user_id );

        $data_user = [
            'active' => 0,
            'password' => Hash::make('otkaz123'),
        ];
        $user = Sentinel::update($user, $data_user);
      
        $send_to = EmailingController::sendTo('employee_terminations', 'create');
        try {
            foreach(array_unique($send_to) as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' ) {
                    Mail::to($send_to_mail)->send(new EmployeeTerminationMail($employeeTermination)); 
                }
            }
        } catch (\Throwable $th) {
            session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
			return redirect()->back();
        }
       

		session()->flash('success', __('ctrl.data_save'));
        
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
        $employee_termination = EmployeeTermination::find($id);

        $employee = $employee_termination->employee;
        $terminations = Termination::orderBy('name','ASC')->get();

        return view('Centaur::employee_terminations.edit', ['employee_termination' => $employee_termination,'employee' => $employee,'terminations' => $terminations]);
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
        $employee_termination = EmployeeTermination::find($id);

        $data = array(
			'employee_id'       => $request['employee_id'],
			'termination_id'    => $request['termination_id'],
			'notice_period'     => $request['notice_period'],
			'check_out_date'    => $request['check_out_date'],
			'comment'  	        => $request['comment'],
		);
     
		$employee_termination->updateEmployeeTermination($data);
		
		session()->flash('success', __('ctrl.data_edit'));
        
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
        
        $employee_termination = EmployeeTermination::find($id);
        $employee_termination->delete();

        $data_empl = array(
			'termination_id'    => null,
			'checkout'          => null
        );
        $employee = $employee_termination->employee;
        $employee->updateEmployee($data_empl);
        
        // za odjavljenog djelatnika - korisnički kodaci deaktivirani
        
        $user = Sentinel::findById( $employee->user_id );

        $data_user = [
            'active' => 1,
            'password' => Hash::make(strstr($user->email, '@', true) ),
        ];

        $user = Sentinel::update($user, $data_user);

        $message = session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
