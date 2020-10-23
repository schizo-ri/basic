<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\EmployeeTraining;
use App\Models\Employee;
use App\Models\Training;


class EmployeeTrainingController extends Controller
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

        $employee_trainings = EmployeeTraining::get();

        return view('Centaur::employee_trainings.index', ['employee_trainings' => $employee_trainings, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $trainings = Training::get();
        $employees = Employee::employees_firstNameASC();

        return view('Centaur::employee_trainings.create',['employees' => $employees, 'trainings' => $trainings]);
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
			'training_id'  	=> $request['training_id'],
			'date'  	    => $request['date'],
			'expiry_date'  	=> $request['expiry_date'],
			'description'  	=> $request['description'],
		);
     
		$employeeTraining = new EmployeeTraining();
		$employeeTraining->saveEmployeeTraining($data);
		
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
        $employeeTraining = EmployeeTraining::find($id);
        
        $trainings = Training::get();
        $employees = Employee::employees_firstNameASC();

        return view('Centaur::employee_trainings.edit',['employees' => $employees,'trainings' => $trainings, 'employeeTraining' => $employeeTraining]);
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
        $employeeTraining = EmployeeTraining::find($id);

        $data = array(
			'employee_id'  	=> $request['employee_id'],
			'training_id'  	=> $request['training_id'],
			'date'  	    => $request['date'],
			'expiry_date'  	=> $request['expiry_date'],
			'description'  	=> $request['description'],
		);
     
		$employeeTraining->updateEmployeeTraining($data);
		
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
        $employeeTraining = EmployeeTraining::find($id);
        $employeeTraining->delete();

        $message = session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
