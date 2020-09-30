<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Employee;
use App\Models\Task;
use Sentinel;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Sentinel::getUser()->employee;
        $date = date('Y-m-d');
        $tasks_group_date = Task::whereDate('date', '>=', $date)->orderBy('date','ASC')->get()->groupBy('date');
        
        return view('Centaur::tasks.index', ['tasks_group_date' => $tasks_group_date]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
        $cars = Car::orderBy('registration','ASC')->get();

        return view('Centaur::tasks.create', ['employees' => $employees, 'cars' => $cars]);
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
			'car_id'  		=> $request['car_id'],
			'title'  		=> $request['title'],
			'date'  		=> $request['date'],
			'time1' 		=> $request['time1'],
			'time2' 		=> $request['time2'],
			'type' 		    => $request['type'],
			'description'   => $request['description']
        );

        $task = new Task();
		$task->saveTask($data);

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
        $task = Task::find($id);
        $empl = Sentinel::getUser()->employee;
        $permission_dep = array();
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
        return view('Centaur::tasks.show', ['task' => $task, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
        $cars = Car::orderBy('registration','ASC')->get();

         return view('Centaur::tasks.edit', ['task' => $task,'employees' => $employees, 'cars' => $cars]);
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
        $task = Task::find($id);

        $data = array(
			'employee_id'  	=> $request['employee_id'],
			'car_id'  		=> $request['car_id'],
			'title'  		=> $request['title'],
			'date'  		=> $request['date'],
			'time1' 		=> $request['time1'],
			'time2' 		=> $request['time2'],
			'type' 		    => $request['type'],
			'description'   => $request['description']
        );
      
		$task->updateTask($data);

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
        $task = Task::find($id);
        $task->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }

    public static function task_for_selected_day ($date) 
    {
        $tasks = Task::whereDate('date', $date)->get();
        return $tasks;
    }
}
