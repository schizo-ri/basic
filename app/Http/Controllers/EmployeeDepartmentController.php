<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Controller;
use App\Models\EmployeeDepartment;
use App\Models\Department;
use App\Models\Employee;

class EmployeeDepartmentController extends Controller
{
    /**
     * Set middleware to quard controller.
     *
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
        $departments = EmployeeDepartment::hasEmployeeDepartment_sort();
       
        $permission_dep = DashboardController::getDepartmentPermission();
        
        return view('Centaur::employee_departments.index', ['departments' => $departments,'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $department = Department::find( $request['department_id']);
        $employees = Employee::employees_lastNameASC();
        
        return view('Centaur::employee_departments.create', ['department' => $department,'employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employeeDepartments = EmployeeDepartment::where('department_id', $request['department_id'])->get();
        if( is_array($request['employee_id']) &&  count($request['employee_id']) > 0) {
            foreach ($request['employee_id'] as $employee_id) {
                if( ! $employeeDepartments->where('employee_id', $employee_id)->first() ) {
                    $data_department = array(
                        'department_id' => $request['department_id'],
                        'employee_id' => $employee_id,
                    );
                    $employeeDepartment = new EmployeeDepartment();
                    $employeeDepartment->saveEmployeeDepartment($data_department);
                }
            }
            foreach ($employeeDepartments as $employeeDepartment) {
                if( ! in_array( $employeeDepartment->employee_id , $request['employee_id'] )) {
                    $employeeDepartment->delete();
                }
            }
    
            $message = session()->flash('success', 'Djelatnici su snimljeni u odjele');
        } else {
            foreach ($employeeDepartments as $employeeDepartment) {
                $employeeDepartment->delete();
            }
            $message = session()->flash('error', 'Nije označen ni jedan djelatnik. ');
        }
		
		return redirect()->back()->withFlashMessage($message);
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
        //
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
        //
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
