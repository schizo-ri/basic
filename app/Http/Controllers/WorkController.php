<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Work;
use App\Models\Employee;
use Sentinel;

class WorkController extends Controller
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
      if($empl) {
        $permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
      } 
		
		if(isset($request->department_id)) {
			$works = Work::where('department_id',$request->department_id)->orderBy('name')->get();
		} else {
			$works = Work::orderBy('name')->get();
		}
    $employees = Employee::where('id','<>',1)->where('checkout',null)->get();

		return view('Centaur::works.index', ['works' => $works, 'employees' => $employees,'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      $departments = Department::orderBy('name', 'ASC')->get();
      $employees = Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->orderBy('users.last_name', 'ASC')->where('employees.id','<>',1)->where('employees.checkout',null)->get();
      
      if(isset($request->department_id)) {
        $department1 = Department::find($request->department_id);
        return view('Centaur::works.create', ['departments' => $departments,'department1' => $department1,'employees' => $employees]);
      } else {
        return view('Centaur::works.create', ['departments' => $departments,'employees' => $employees]);
      }
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
			'department_id'  	=> $request['department_id'],
			'name'  			=> $request['name'],
			'job_description'   => $request['job_description'],
			'employee_id'	 	=> $request['employee_id']
		);
		
		$work = new Work();
		$work->saveWork($data);
		
		session()->flash('success',  __('ctrl.data_save'));
        return redirect()->back();	
     //   return redirect()->route('works.index');
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
        $work = Work::find($id);
        $departments = Department::orderBy('name', 'ASC')->get();
        $employees = Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->orderBy('users.last_name', 'ASC')->where('employees.id','<>',1)->where('employees.checkout',null)->get();
       
        return view('Centaur::works.edit', ['work' => $work,'departments' => $departments,'employees' => $employees]);
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
        $work = Work::find($id);
		
		$data = array(
			'department_id'  	=> $request['department_id'],
			'name'  			=> $request['name'],
			'job_description'   => $request['job_description'],
			'employee_id'	 	=> $request['employee_id']
		);

		$work->updateWork($data);
		
		session()->flash('success', __('ctrl.data_edit'));
        return redirect()->back();	
      //  return redirect()->route('works.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $work = Work::find($id);
		$work->delete();
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
