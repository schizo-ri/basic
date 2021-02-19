<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkDiary;
use App\Models\WorkTask;
use App\Models\Employee;
use App\Models\Project;
use App\Models\WorkDiaryItem;
use App\Http\Controllers\ApiController;
use Sentinel;
use Log;

class WorkDiaryController extends Controller
{
    private $api_erp;
    private $api_project;

    /**
	*
	* Set middleware to quard controller.
	* @return void
	*/
	public function __construct()
	{
        $this->middleware('sentinel.auth');
        $this->api_erp = true;
        $this->api_project = true;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workDiaries_date = WorkDiary::get();

        $workTasks = WorkTask::get()->pluck('name','id');
        
        $hours = 0;
        $task = null;
        $employee_id = null;
        $project= null;
        
        $dates = array();
		foreach (array_keys($workDiaries_date->groupBy('date')->toArray()) as $workDiary_date) {
            array_push($dates, date('Y-m',strtotime($workDiary_date)) );
        }
        $dates = array_unique( $dates);
        rsort($dates );
        $projects = new WorkDiary();
        $projects = $projects->getProjects( $workDiaries_date ); 

        $employees = new WorkDiary();
        $employees = $employees->getEmployees( $workDiaries_date ); 
        
        if(isset( $request['date'])) {
            $date = $request['date'];
        } else {
            $date = date('Y-m');
        }
        
        if ( isset( $request[ 'task']) && $request[ 'task'] != 'null' ) {
            $task = $request[ 'task']; 
        }
        if ( isset( $request[ 'employee_id']) && $request[ 'employee_id'] != 'null' ) {
            $employee_id = $request[ 'employee_id']; 
        }
        if ( isset( $request[ 'project']) && $request[ 'project'] != 'null' ) {
            $project = $request[ 'project']; 
        }

        $workDiaries = new WorkDiary();
        $workDiaries = $workDiaries->getTasks( $date, $task, $employee_id, $project );
        Log::info( $workDiaries );
        $sum_time = WorkDiary::sumTasks( $workDiaries ); 

        return view('Centaur::work_diaries.index', ['workDiaries' => $workDiaries,'dates' => $dates,'projects' => $projects, 'employees' => $employees, 'sum_time' => $sum_time, 'workTasks' => $workTasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $workTasks = WorkTask::get();
        $employees = Employee::employees_lastNameASC();
        $employee =  Sentinel::getUser()->employee;
              
        $projects = null;
        $tasks = null;
        
        if( isset($request['date'])) {
            $date = $request['date'];
        } else {
            $date = date('Y-m-d');
        }

        if( $employee ) {
            if( $this->api_erp ) {
                $erp_id = $employee->erp_id;
                Log::info('employee' . $erp_id);               
                
                $api = new ApiController();
                $projects_erp = $api->get_employee_available_projects( $erp_id, $date );

                $api = new ApiController();
                if( $projects_erp != null && count($projects_erp) > 0) {
                    $tasks = $api->get_employee_project_tasks( $erp_id, $date, array_keys($projects_erp)[0]);
                }
                $projects = Project::where('active',1)->get();
               
               /*  $projects = null; */
            } else {
                $tasks = null;
                $projects_erp = null;
                $projects = Project::where('active',1)->get();
            }
        }
        
        return view('Centaur::work_diaries.create', ['workTasks' => $workTasks, 'employees' => $employees,'projects' => $projects,'projects_erp' => $projects_erp,'tasks' => $tasks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        $seconds = 0;
        $project_overtime = null;

        $employee =  Employee::find($request['employee_id']);
        $erp_employee = $employee->erp_id;   
        $request_exist = WorkDiary::where('employee_id', $employee->id )->where('date',$request['date'])->first();
        
        if( $request_exist  ) {
            session()->flash('error',  __('ctrl.record_exist'));
            return redirect()->back();
        } else {
            foreach ( $request['project_id'] as $proj_key => $proj_id ) {
                /*** Projekt */
                if( $this->api_project && $erp_employee ) {
                    $api = new ApiController();
                    $projects_erp = $api->get_employee_available_projects( $erp_employee, $request['date'] );
                    $project_erp = $projects_erp [$proj_id ];
                    $erp_id = str_replace("]","", str_replace("[","",strstr( $project_erp," ", true )));
                    $project = $erp_id ? Project::where('erp_id', $erp_id)->first() : null;
                    $project_id = $project ? $project->id : null;
                }

                $data = array(
                    'date'  	    => $request['date'],
                    'employee_id'   => $employee->id,
                    'project_id'    => $this->api_project ? $project_id : ($request['project_id'] ? $request['project_id'] : null),
                    'erp_task_id'   => isset($request['erp_task_id']) ? $request['erp_task_id'][$proj_key] : null,
                    'start_time'  	=> isset($request['start_time'] ) ? $request['start_time'] : null,
                    'end_time'  	=> isset($request['end_time']) ? $request['end_time'] : null,
                );
        
                $workDiary = new WorkDiary();
                $workDiary->saveWorkDiary($data);

                if(isset($request['project_overtime'] ) && $request['project_overtime']) {
                    if( $this->api_project && $erp_employee ) {                        
                        $project_erp = $projects_erp [ $request['project_overtime'] ];
                        $erp_id = str_replace("]","", str_replace("[","",strstr( $project_erp," ", true )));
                        $project = $erp_id ? Project::where('erp_id', $erp_id)->first() : null;
                        $project_id_overtime = $project ? $project->id : null;
                    }
                    if( $project_id_overtime ==  $workDiary->project_id) {
                        $project_overtime = $workDiary;
                    }

                }
                foreach ( $request['task_id'][$proj_key] as $key => $task_id ) {
                    if($request['time'][$proj_key][$key] != null && $request['time'][$proj_key][$key] != '' && $request['time'][$proj_key][$key] != '00:00') {
                        $dataItem = array(
                            'diary_id'  	=> $workDiary->id,
                            'task_id'  	    => $task_id,
                            'time'  	    => $request['time'][$proj_key][$key],
                            'description'  	=> $request['description'][$proj_key][$key],
                        );
                        $workDiaryItem = new WorkDiaryItem();
                        $workDiaryItem->saveWorkDiaryItem($dataItem);
        
                        list($hour,$minute) = explode(':', $workDiaryItem->time);
                        $seconds += $hour*3600;
                        $seconds += $minute*60;
                    }
                }
            }
            
            $afterhours =  $seconds - 28800;
            $send_afterhourRequest = '';
            if( $afterhours > 0 && $project_overtime) {
                $send_afterhourRequest = AfterhourController::storeAfterHour( $project_overtime );
            } 
    
            session()->flash('success', __('ctrl.data_save') . " Spremljeno je ukupno " .  gmdate("H:i:s", $seconds)  . ' sati rada. ' . $send_afterhourRequest);
          
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id // employee_id
     * @return \Illuminate\Http\Response
     */
    public function show( $id, Request $request )
    {
        if(isset( $request['date'])) {
            $date = $request['date'];
        } else {
            $date = date('Y-m');
        }

        $workDiaries_date = WorkDiary::whereMonth('date', date('m', strtotime($date .'-01') ))->whereYear('date', date('Y', strtotime($date .'-01') ))->get();
      
        $employee_id = null;
        
        if( ! Sentinel::inRole('administrator') ) {           
            $employee =  Sentinel::getUser()->employee; 
            $workDiaries_date = WorkDiary::where('employee_id', $employee->id)->whereMonth('date', date('m', strtotime($date .'-01') ))->whereYear('date', date('Y', strtotime($date .'-01') ))->get();
            $employee_id = $employee->id;
        }
      
        $workTasks = WorkTask::get()->pluck('name','id');
        
        $task = null;
        $project= null;
        
        $dates = array();
		foreach (array_keys(WorkDiary::get()->groupBy('date')->toArray()) as $workDiary_date) {
            array_push($dates, date('Y-m',strtotime($workDiary_date)) );
        }
        $dates = array_unique( $dates);
        rsort( $dates );

        $projects = new WorkDiary();
        $projects = $projects->getProjects( $workDiaries_date ); 
       
        $employees = new WorkDiary();
        $employees = $employees->getEmployees( $workDiaries_date ); 

        if ( isset( $request[ 'task']) && $request[ 'task'] != 'null' ) {
            $task = $request[ 'task']; 
        }
        if ( isset( $request[ 'employee_id']) && $request[ 'employee_id'] != 'null' ) {
            $employee_id = $request[ 'employee_id']; 
        }
        if ( isset( $request[ 'project']) && $request[ 'project'] != 'null' ) {
            $project = $request[ 'project']; 
        }

        $workDiaries = new WorkDiary();
        $workDiaries = $workDiaries->getTasks( $date, $task, $employee_id, $project );
      
        $sum_time = WorkDiary::sumTasks( $workDiaries ); 

        return view('Centaur::work_diaries.show', ['workDiaries' => $workDiaries,'dates' => $dates,'projects' => $projects, 'employees' => $employees, 'sum_time' => $sum_time, 'workTasks' => $workTasks]);
    }
  
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $workDiary = WorkDiary::with('hasWorkDiaryItem')->find($id);
        $employee = $workDiary->employee;

        $sum = WorkDiary::sumTask( $workDiary ) / 3600;  // ukupno vrijeme za dan 
       
        $workTasks = WorkTask::get();
        $employees = Employee::employees_lastNameASC();
        $projects = null;
        $tasks = null;

        if( isset($request['date'])) {
            $date = $request['date'];
        } else {
            $date = date('Y-m-d');
        }

        if( $employee ) {
            if( $this->api_erp ) {               
                $erp_id = $employee->erp_id;
                $api = new ApiController();
                $projects_erp = $api->get_employee_available_projects( $erp_id, $workDiary->date );
                $project = Project::find($workDiary->project_id);
               /*  dd($project->erp_id); //P-2871 */
                if(  $project->erp_id ) {
                    $project_erp = array_filter($projects_erp, function($value) use ($project) {
                        return strpos($value, $project->erp_id) !== false;
                    });
                }
               
                if( isset(array_keys($project_erp)[0])) {
                    $key_project =  array_keys($project_erp)[0];
                } else {
                    $key_project = null;
                }
               
                $api = new ApiController();
                $tasks = $api->get_employee_project_tasks( $erp_id, $workDiary->date,  $key_project);
               
            } else {
                $tasks = null;
                $projects = Project::where('active',1)->get();
            }
        }

        return view('Centaur::work_diaries.edit', ['workDiary' => $workDiary, 'sum' => $sum, 'workTasks' => $workTasks,'projects' => $projects,'projects_erp' => $projects_erp,'tasks' => $tasks, 'employees' => $employees, 'key_project' => $key_project]);
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
        $workDiary = WorkDiary::find($id);
        $employee = $workDiary->employee;
        $seconds = 0;

        /*** Projekt */
           if( $this->api_project ) {
            $erp_employee = $employee->erp_id;
            
            $api = new ApiController();
            $projects_erp = $api->get_employee_available_projects( $erp_employee, $request['date'] );
          
            $project_erp = $projects_erp[$request['project_id'] ];
          
            $erp_id = str_replace("]","", str_replace("[","",strstr( $project_erp," ", true )));
        
            $project = $erp_id ? Project::where('erp_id', $erp_id)->first() : null;
            $project_id = $project ? $project->id : null;
        }
   
        /*** Projekt */

        $data = array(
            'date'  	    => $request['date'],
            'employee_id'   => $request['employee_id'],
            'project_id'    => $this->api_project ? $project_id : ($request['project_id'] ? $request['project_id'] : null),
            'erp_task_id'   => isset($request['erp_task_id']) ? $request['erp_task_id'] : null,
            'start_time'  	=> isset($request['start_time'] ) ? $request['start_time'] : null,
            'end_time'  	=> isset($request['end_time']) ? $request['end_time'] : null,
         /*    'description'  	=> $request['description'], */
        );

        $workDiary->updateWorkDiary($data);

        foreach ( $request['task_id'] as $key => $task_id ) {
            $workDiaryItem = WorkDiaryItem::where( 'diary_id', $workDiary->id )->where( 'task_id', $task_id )->first();
           
            if($request['time'][$key] != null && $request['time'][$key] != '' && $request['time'][$key] != '00:00') {
                $dataItem = array(
                    'diary_id'  	=> $workDiary->id,
                    'task_id'  	    => $task_id,
                    'time'  	    => $request['time'][$key],
                    'description'  	=> $request['description'][$key],
                );
                if( $workDiaryItem ) {
                    $workDiaryItem->updateWorkDiaryItem($dataItem);
                } else {
                    $workDiaryItem = new WorkDiaryItem();
                    $workDiaryItem->saveWorkDiaryItem($dataItem);
                }

                list($hour,$minute) = explode(':', $workDiaryItem->time);
                $seconds += $hour*3600;
                $seconds += $minute*60;
            }
        }
        
        $afterhours =  $seconds - 28800;

        $request_exist = BasicAbsenceController::afterhoursForDay( $workDiary->employee_id,  $workDiary->date,  $workDiary->start_time,  $workDiary->end_time );
       
        $send_afterhourRequest = '';
        if( $request_exist == 0  ) {
            if( $afterhours > 0) {
               $send_afterhourRequest = AfterhourController::storeAfterHour($workDiary);
            } 
        } else {
            $send_afterhourRequest = "Zahtjev za prekovremene sate već postoji.";
        }

        session()->flash('success', __('ctrl.data_save') . " Spremljeno je ukupno " .  gmdate("H:i:s", $seconds)  . ' sati rada. ' . $send_afterhourRequest);
      
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
        $workDiary = WorkDiary::find($id);

        if($workDiary) {
            foreach ($workDiary->hasWorkDiaryItem as $item ) {
                $item->delete();
            }
    
            $workDiary->delete();
        }
       

        $message = session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
