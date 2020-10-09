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
        $employees = Employee::join('users','employees.user_id','users.id')->select('employees.*', 'users.first_name', 'users.last_name')->orderBy('last_name','ASC')->where('employees.id','<>',1)->where('employees.checkout',null)->get();
        
        $tables1 = Table::get();
		$models = array();
		
		foreach($tables1 as $table) {
            //array_push($tables, $table->name);
            $models[$table->name] = $table->description;
        }
      
        asort($models);
		
        $methods = array();

        $methods['create'] = __('basic.create');
        $methods['update'] = __('basic.update');
        $methods['view'] = __('basic.view');
        $methods['delete'] = __('basic.delete');
        $methods['cron'] = __('basic.cron');
        $methods['activate'] = __('basic.activate');
        $methods['confirm'] = __('basic.confirm');

		return view('Centaur::emailings.index', ['emailings' => $emailings,'methods' => $methods,'models' => $models, 'departments' => $departments, 'employees' => $employees, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tables1 = Table::get();
		$models = array();
		
		foreach($tables1 as $table) {
            //array_push($tables, $table->name);
            $models[$table->id] = $table->description;
        }
      
        asort($models);
		
        $methods = array();

        $methods['create'] = __('basic.create');
        $methods['update'] = __('basic.update');
        $methods['view'] = __('basic.view');
        $methods['delete'] = __('basic.delete');
        $methods['cron'] = __('basic.cron');
        $methods['activate'] = __('basic.activate');
        $methods['confirm'] = __('basic.confirm');


        $departments = Department::where('email','<>', null)->orderBy('name', 'ASC')->get();
        $employees = Employee::join('users','employees.user_id','users.id')->select('employees.*', 'users.first_name', 'users.last_name')->orderBy('last_name','ASC')->where('employees.id','<>',1)->where('employees.checkout',null)->get();
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
        if(Emailing::where('model', $request['model'] )->where('method', $request['method'] )->first()) {
            session()->flash('success',  __('ctrl.method_exist'));
            
            return redirect()->back();
        } else {
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
            
            session()->flash('error',  __('ctrl.data_save'));
            return redirect()->back();
           // return redirect()->route('emailings.index');
        }        
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
        $departments = Department::where('email','<>', null)->orderBy('name', 'ASC')->get();
      
        $employees = Employee::join('users','employees.user_id','users.id')->select('employees.*', 'users.first_name', 'users.last_name')->orderBy('last_name','ASC')->where('employees.id','<>',1)->where('employees.checkout',null)->get();
        $tables1 = Table::get();
		$models = array();
		
		foreach($tables1 as $table) {
            //array_push($tables, $table->name);
            $models[$table->id] = $table->description;
        }
      
        asort($models);
		
        $methods = array();

        $methods['create'] = __('basic.create');
        $methods['update'] = __('basic.update');
        $methods['view'] = __('basic.view');
        $methods['delete'] = __('basic.delete');
        $methods['cron'] = __('basic.cron');
        $methods['activate'] = __('basic.activate');
        $methods['confirm'] = __('basic.confirm');

		return view('Centaur::emailings.edit', ['emailing'=>$emailing, 'models' => $models, 'tables1' => $tables1, 'departments' => $departments, 'employees' => $employees, 'methods' => $methods]);

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
		
		if($request['sent_to_empl']){
			$sent_to_empl = implode(",", $request['sent_to_empl']);
		}
		if($request['sent_to_dep']){
			$sent_to_dep = implode(",", $request['sent_to_dep']);
		}
		$data = array(
			'model'  		=> $request['model'],
			'method'  		=> $request['method'],
			'sent_to_dep'   => $sent_to_dep,
			'sent_to_empl' 	=> $sent_to_empl
		);
		
		$emailing->updateEmailing($data);
		
		session()->flash('success', __('ctrl.data_edit'));
		
		return redirect()->back();
	//  return redirect()->route('emailings.index');
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
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }

    public static function sendTo ($table, $method) 
    {
        $emailings = Emailing::get();
        $send_to = array();
        $departments = Department::get();
       /*  $employees = Employee::where('id','<>',0)->where('checkout',null)->get(); */
        $employees = Employee::where('checkout',null)->get();

        if(isset($emailings)) {
            foreach($emailings as $emailing) {
                if($emailing->table['name'] == $table && $emailing->method == $method) {
                    
                    if($emailing->sent_to_dep) {
                        foreach(explode(",", $emailing->sent_to_dep) as $prima_dep) {
                            array_push($send_to, $departments->where('id', $prima_dep)->first()->email );
                        }
                    }
                    if($emailing->sent_to_empl) {
                        foreach(explode(",", $emailing->sent_to_empl) as $prima_empl) {
                            array_push($send_to, $employees->where('id', $prima_empl)->first()->email );
                        }
                    }
                }
            }
        }

        return array_unique($send_to);
    }
}
