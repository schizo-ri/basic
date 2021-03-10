<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentRole;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Work;
use Sentinel;

class DepartmentController extends Controller
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
        $departments = Department::orderBy('name')->get();
        $department_roles = DepartmentRole::get();
		$works = Work::get();
       
		return view('Centaur::departments.index', ['departments' => $departments, 'works' => $works, 'department_roles' => $department_roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::get();
		$departments = Department::where('level1', 1)->orWhere('level1', 0)->orderBy('name','ASC')->get();
        $employees = Employee::employees_firstNameASC();
        
		return view('Centaur::departments.create', ['companies' => $companies, 'departments' => $departments, 'employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
		$data = array(
			'company_id'  		=> $request['company_id'],
			'name'  			=> $request['name'],
			'email'     		=> $request['email'],
			'level1'	 		=> $request['level1'],
			'level2'	 		=> $request['level2'],
			'employee_id'	 	=> $request['employee_id']
		);
		
		$department = new Department();
		$department->saveDepartment($data);
		
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
        $department = Department::find($id);
        $companies = Company::get();
        $employees = Employee::employees_firstNameASC();
		$departments = Department::where('level2','<>', $department->id )->where('level1', 1)->orWhere('level1', 0)->orderBy('name','ASC')->get();
		
		return view('Centaur::departments.edit', ['department' => $department,'companies' => $companies,'departments' => $departments, 'employees' => $employees]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, $id)
    {
		$department = Department::find($id);

		$data = array(
			'company_id'  		=> $request['company_id'],
			'name'  			=> $request['name'],
			'email'     		=> $request['email'],
			'level1'	 		=> $request['level1'],
			'level2'	 		=> $request['level2'],
			'employee_id'	 	=> $request['employee_id']
		);
		
		$department->updateDepartment($data);
		
		session()->flash('success',__('ctrl.data_edit'));
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
        $department = Department::find($id);
		$department->delete();
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
