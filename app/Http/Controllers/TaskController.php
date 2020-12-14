<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Car;
use App\Models\Employee;
use App\Models\Task;
use App\Models\EmployeeTask;
use Sentinel;
use App\Mail\TaskCreateMail;
use App\Mail\TaskInfoMail;
use Illuminate\Support\Facades\Mail;
use Log;

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
        $employees = Employee::employees_lastNameASC();
        $date = date('Y-m-d');
        $tasks = Task::orderBy('start_date','ASC')->get();
       
        if(! Sentinel::inRole('administrator')) {
            $tasks = $tasks->where('to_employee_id',$employee->id );
        }
        
        $permission_dep = DashboardController::getDepartmentPermission();
        return view('Centaur::tasks.index', ['tasks' => $tasks, 'employees' => $employees,'permission_dep' => $permission_dep]);
    }

    public function openTaskList()
    {
        $employee = Sentinel::getUser()->employee;
        $date = date('Y-m-d');

        $tasks_employee = EmployeeTask::whereDate('created_at', '>=', $date)->orderBy('created_at','ASC')->get();
        if( ! Sentinel::inRole('administrator')) {
            $tasks_employee = $tasks_employee->where('employee_id',$employee->id );
        }

        return view('Centaur::tasks.task_list', ['tasks_employee' => $tasks_employee]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::employees_lastNameASC();
        $cars = Car::orderBy('model','ASC')->get();

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
        $employee = Sentinel::getUser()->employee;
      
        if(! $request['to_employee_id']) {
            session()->flash('error', 'Nemoguće spremiti zadatak bez upisanih djelatnika!');
            return redirect()->back();
        }

        $employees_id = implode(",",$request['to_employee_id']);
        $data = array(
			'employee_id'  	    => $employee->id,
			'to_employee_id'    => $employees_id,
			'car_id'  		    => $request['car_id'],
            'task'  		    => $request['task'],
            'description'       => $request['description'],
			'start_date'  	    => $request['start_date'],
			'end_date'  	    => $request['end_date'],
            'interval_period'   => $request['interval_period'],
            'energy_consumptions'=> $request['energy_consumptions'],
			'active' 		    => $request['active'],
        );

        $task = new Task();
		$task->saveTask($data);

        // spremanje dnevnog zadatka i slanje maila
        if( $task->start_date == date('Y-m-d') ) {
            foreach ($request['to_employee_id'] as $key => $employee_id) {

                if($key == 0) {
                    $data_task = array(
                        'task_id'  	    => $task->id,
                        'employee_id'  	=> $employee_id,
                        'comment'  	    => $request['comment']            
                    );
            
                    $employeeTask = new EmployeeTask();
                    $employeeTask->saveEmployeeTask( $data_task );

                    if( $task->energy_consumptions == 1 ) {
                        $user = Sentinel::findById( $employeeTask->employee->user_id );
                      
                        Log($user->inRole('energenti'));
                        Log($user->inRole('administrator'));
                        $role = Sentinel::findRoleBySlug('energenti');
                      
                        if( ! $user->inRole('energenti') ) {
                            $role->users()->attach($user);
                        }
                    }

                    $email = $employeeTask->employee->email;
                    if($email != null && $email != '') {
                        try {
                            Mail::to($email)->send(new TaskCreateMail($employeeTask));
                        } catch (\Throwable $th) {
                            $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Nešto je pošlo krivo!');
                            return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                        }
                    } else {
                        $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Provjeri mail adresu djelatnika');
                        return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                    }
                }  
            }            
        } else {
            foreach ($request['to_employee_id'] as $employee_id) {
                $employee = Employee::where('id', $employee_id )->first();

                if( $task->energy_consumptions == 1 ) {
                    $user = Sentinel::findById( $employee->user_id );

                    Log($user->inRole('energenti'));
                    Log($user->inRole('administrator'));
                    $role = Sentinel::findRoleBySlug('energenti');
                    if( ! $user->inRole('energenti') ) {
                        $role->users()->attach($user);
                    }
                }

                $email = $employee->email;
                if($email != null && $email != '') {
                    try {
                      Mail::to( $email)->send(new TaskInfoMail($employeeTask));
                    } catch (\Throwable $th) {
                        $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Nešto je pošlo krivo!');
                        return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                    }
                } else {
                    $message = session()->flash('error', 'Uspješno je spremljen novi zadatak, ali mail nije poslan. Provjeri mail adresu djelatnika');
                    return redirect()->route('admin.tasks.index')->withFlashMessage($message);
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
        $employees = Employee::employees_lastNameASC();
        $cars = Car::orderBy('model','ASC')->get();

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
        if(! $request['to_employee_id']) {
            $message = session()->flash('error', 'Nemoguće spremiti zadatak bez upisanih djelatnika!');
            
            return redirect()->back()->withFlashMessage($message);
        } 
        $employee = Sentinel::getUser()->employee;
        $task = Task::find( $id );

        $employees_id = implode(",",$request['to_employee_id']);
        $data = array(
			'employee_id'  	    => $employee->id,
			'to_employee_id'    => $employees_id,
			'car_id'  		    => $request['car_id'],
            'task'  		    => $request['task'],
            'description'       => $request['description'],
			'start_date'  	    => $request['start_date'],
			'end_date'  	    => $request['end_date'],
            'interval_period'   => $request['interval_period'],
            'energy_consumptions'=> $request['energy_consumptions'],
			'active' 		    => $request['active'],
        );

		$task->updateTask($data);
        // spremanje dnevnog zadatka i slanje maila
        if( $task->start_date == date('Y-m-d') ) {
            foreach ($request['to_employee_id'] as $key => $employee_id) {
                if($key == 0) {
                    $employeeTask = EmployeeTask::where('employee_id', $employee_id)->where('task_id', $task->id )->whereDate('created_at',date('Y-m-d'))->first();
                    if(! $employeeTask) {
                        $data_task = array(
                            'task_id'  	    => $task->id,
                            'employee_id'  	=> $employee_id,
                            'comment'  	    => $request['comment']            
                        );
                
                        $employeeTask = new EmployeeTask();
                        $employeeTask->saveEmployeeTask($data_task);
    
                        if( $task->energy_consumptions == 1 ) {
                            $user = Sentinel::findById( $employeeTask->employee->user_id );
    
                            Log($user->inRole('energenti'));
                            Log($user->inRole('administrator'));
                            $role = Sentinel::findRoleBySlug('energenti');
                            if( ! $user->inRole('energenti') ) {
                                $role->users()->attach($user);
                            }
                        }

                        $email = $employeeTask->employee->email;
                        if($email != null && $email != '') {
                            try {
                                Mail::to($email)->send(new TaskCreateMail($employeeTask));
                            } catch (\Throwable $th) {
                                $message = session()->flash('error', 'Uspješno je ispravljen zadatak, ali mail nije poslan. Nešto je pošlo krivo!');
                                return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                            }
                        } else {
                            $message = session()->flash('error', 'Uspješno je ispravljen zadatak, ali mail nije poslan. Provjeri mail adresu djelatnika');
                            return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                        }
                    }
                }  
            }
        } else {
            foreach ($request['to_employee_id'] as $employee_id) {
                $employee = Employee::where('id', $employee_id )->first();

                if( $task->energy_consumptions == 1 ) {
                    $user = Sentinel::findById( $employee->user_id );

                    Log($user->inRole('energenti'));
                    Log($user->inRole('administrator'));
                    $role = Sentinel::findRoleBySlug('energenti');
                    if( ! $user->inRole('energenti') ) {
                        $role->users()->attach($user);
                    }
                }

                $email = $employee->email;
                if($email != null && $email != '') {
                    try {
                    Mail::to( $email)->send(new TaskInfoMail($employeeTask));
                    } catch (\Throwable $th) {
                        $message = session()->flash('error', 'Uspješno je ispravljen zadatak, ali mail nije poslan. Nešto je pošlo krivo!');
                        return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                    }
                } else {
                    $message = session()->flash('error', 'Uspješno je ispravljen zadatak, ali mail nije poslan. Provjeri mail adresu djelatnika');
                    return redirect()->route('admin.tasks.index')->withFlashMessage($message);
                }
             }           
        } 

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
        $tasks = Task::whereDate('start_date', $date)->get();
        return $tasks;
    }
}
