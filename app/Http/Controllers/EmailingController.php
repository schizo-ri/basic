<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmailingRequest;
use App\Http\Controllers\Controller;
use App\Models\Emailing;
use App\Models\Table;
use App\Models\Department;
use App\Models\Employee;
use Sentinel;

class EmailingController extends Controller
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
        $empl = Sentinel::getUser()->employee;
        $permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
		
		$emailings = Emailing::join('tables','emailings.model','tables.id')->select('emailings.*','tables.name')->orderBy('tables.name','ASC')->get();
		$departments = Department::orderBy('name', 'ASC')->get();
		$employees = Employee::join('users','employees.user_id','users.id')->select('employees.*', 'users.first_name', 'users.last_name')->orderBy('last_name','ASC')->get();
		 
		return view('Centaur::emailings.index', ['emailings' => $emailings, 'departments' => $departments, 'employees' => $employees, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $models = Table::where('emailing',1)->orderBy('name', 'ASC')->get();
        $departments = Department::where('email','<>', null)->orderBy('name', 'ASC')->get();
        $employees = Employee::join('users','employees.user_id','users.id')->select('employees.*', 'users.first_name', 'users.last_name')->orderBy('last_name','ASC')->get();
		$methods = array('activate','create', 'update', 'confirm', 'cron');

		return view('Centaur::emailings.create', ['models' => $models, 'departments' => $departments, 'employees' => $employees, 'methods' => $methods]);
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailingRequest $request)
    {
		$sent_to_empl = '';
	    $sent_to_dep = '';
		
		if($request->sent_to_empl){
			$sent_to_empl = implode(",",$request->sent_to_empl);
		}
		if($request->sent_to_dep){
			$sent_to_dep = implode(",",$request->sent_to_dep);
		}

		$data = array(
			'model'  		=> $request['model'],
			'method'  		=> $request['method'],
			'sent_to_dep'   => $sent_to_dep,
			'sent_to_empl' 	=> $sent_to_empl
		);
		
		$emailing = new Emailing();
		$emailing->saveEmailing($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('emailings.index');
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
        $emailing = Emailing::find($id);
		
		$models = Table::where('emailing',1)->orderBy('name', 'ASC')->get();
        $departments = Department::where('email','<>', null)->orderBy('name', 'ASC')->get();
        $employees = Employee::join('users','employees.user_id','users.id')->select('employees.*', 'users.first_name', 'users.last_name')->orderBy('last_name','ASC')->get();
		$methods = array('activate','create', 'update', 'confirm', 'cron');

		return view('Centaur::emailings.edit', ['emailing'=>$emailing, 'models' => $models, 'departments' => $departments, 'employees' => $employees, 'methods' => $methods]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmailingRequest $request, $id)
    {
        $emailing = Emailing::find($id);
		
		$sent_to_empl = '';
	    $sent_to_dep = '';
		
		if($request->sent_to_empl){
			$sent_to_empl = implode(",",$request->sent_to_empl);
		}
		if($request->sent_to_dep){
			$sent_to_dep = implode(",",$request->sent_to_dep);
		}
	   
		$data = array(
			'model'  		=> $request['model'],
			'method'  		=> $request['method'],
			'sent_to_dep'   => $sent_to_dep,
			'sent_to_empl' 	=> $sent_to_empl
		);
		
		$emailing->updateEmailing($data);
		
		session()->flash('success', "Podaci su ispravljeni");
		
        return redirect()->route('emailings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $emailing = Emailing::find($id);
		$emailing->delete();
		
		$message = session()->flash('success', 'Emailing je uspješno obrisan');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
