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

class WorkDiaryController extends Controller
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
        $workDiaries_date = WorkDiary::get();

        $workTasks = WorkTask::get()->pluck('name','id');
        $employees = Employee::employees_getNameASC();
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
           /*  $api = new ApiController();
            $erp_id = $employee->erp_id;
            $tasks = $api->get_employee_project_tasks( $erp_id, $date );
            $projects = null;
            */
            $tasks = null;
            $projects = Project::where('active',1)->get();
        }
      
        return view('Centaur::work_diaries.create', ['workTasks' => $workTasks, 'employees' => $employees,'projects' => $projects,'tasks' => $tasks]);
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

        $request_exist = WorkDiary::where('employee_id',$request['employee_id'])->where('date',$request['date'])->first();
            
        if( $request_exist  ) {
            session()->flash('error',  __('ctrl.record_exist'));
            return redirect()->back();
        } else {
            $data = array(
                'date'  	    => $request['date'],
                'employee_id'   => $request['employee_id'],
                'project_id'    => $request['project_id'] ? $request['project_id'] : null,
                'erp_task_id'   => isset($request['erp_task_id']) ? $request['erp_task_id'] : null,
                'start_time'  	=> isset($request['start_time'] ) ? $request['start_time'] : null,
                'end_time'  	=> isset($request['end_time']) ? $request['end_time'] : null,
             /*    'description'  	=> $request['description'], */
            );
    
            $workDiary = new WorkDiary();
            $workDiary->saveWorkDiary($data);
    
            foreach ( $request['task_id'] as $key => $task_id ) {
                if($request['time'][$key] != '' && $request['time'][$key] != '00:00') {
                    $dataItem = array(
                        'diary_id'  	=> $workDiary->id,
                        'task_id'  	    => $task_id,
                        'time'  	    => $request['time'][$key],
                        'description'  	=> $request['description'][$key],
                    );
                    $workDiaryItem = new WorkDiaryItem();
                    $workDiaryItem->saveWorkDiaryItem($dataItem);
    
                    list($hour,$minute) = explode(':', $workDiaryItem->time);
                    $seconds += $hour*3600;
                    $seconds += $minute*60;
                }
            }
    
            $afterhours =  $seconds - 28800;
            $send_afterhourRequest = '';
            if( $afterhours > 0) {
                $send_afterhourRequest = AfterhourController::storeAfterHour($workDiary);
            } 
    
            session()->flash('success', __('ctrl.data_save') . " Spremljeno je ukupno " .  gmdate("H:i:s", $seconds)  . ' sati rada. ' . $send_afterhourRequest);
          
            return redirect()->back();
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
        $workDiary = WorkDiary::with('hasWorkDiaryItem')->find($id);
        $employee = $workDiary->employee;

        $sum = WorkDiary::sumTask( $workDiary ) / 3600;  // ukupno vrijeme za dan 
       
        $workTasks = WorkTask::get();
        $employees = Employee::employees_lastNameASC();
        $projects = null;
        $tasks = null;
        
        if( $employee ) {
            /* $api = new ApiController();
            $erp_id = $employee->erp_id;
            $tasks = $api->get_employee_project_tasks( $erp_id, $date );
            $projects = null; */
            
            $tasks = null;
            $projects = Project::where('active',1)->get();
        }

        return view('Centaur::work_diaries.edit', ['workDiary' => $workDiary, 'sum' => $sum, 'workTasks' => $workTasks,'projects' => $projects,'tasks' => $tasks, 'employees' => $employees]);
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
        $seconds = 0;
        $data = array(
            'date'  	    => $request['date'],
            'employee_id'   => $request['employee_id'],
            'project_id'    => $request['project_id'] ? $request['project_id'] : null,
            'erp_task_id'   => isset($request['erp_task_id']) ? $request['erp_task_id'] : null,
            'start_time'  	=> isset($request['start_time'] ) ? $request['start_time'] : null,
            'end_time'  	=> isset($request['end_time']) ? $request['end_time'] : null,
         /*    'description'  	=> $request['description'], */
        );

        $workDiary->updateWorkDiary($data);

        foreach ( $request['task_id'] as $key => $task_id ) {
            $workDiaryItem = WorkDiaryItem::where( 'diary_id', $workDiary->id )->where( 'task_id', $task_id )->first();
           
            if($request['time'][$key] != '' && $request['time'][$key] != '00:00') {
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
