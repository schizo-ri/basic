<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sentinel;
class WorkDiary extends Model
{
    /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
       'employee_id','date','erp_task_id','project_id','start_time','end_time','description'];
       /* 'employee_id','date','ERP_leave_type','project_id','task_id','time','start_time','end_time','description']; */

    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 

	 /*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $projectModel = 'App\Models\Project';     
   
	/*
	* The Eloquent WorkDiaryItem model name
	* 
	* @var string
	*/
    protected static $workDiaryItemModel = 'App\Models\WorkDiaryItem'; 
    
    /*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}
	
	/*
	* Returns the project relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function project()
	{
		return $this->belongsTo(static::$projectModel,'project_id');
    }

	/*
	* Returns the WorkDiary relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
    */

	public function hasWorkDiaryItem()
	{
		return $this->hasMany(static::$workDiaryItemModel,'diary_id');
	}

    /*
	* Save WorkDiary
	* 
	* @param array $workDiary
	* @return void
	*/
	public function saveWorkDiary($workDiary=array())
	{
		return $this->fill($workDiary)->save();
	}
	
	/*
	* Update WorkDiary
	* 
	* @param array $workDiary
	* @return void
	*/
	
	public function updateWorkDiary($workDiary=array())
	{
		return $this->update($workDiary);
    }	   
    
    public function getTasks ( $date, $task, $employee_id, $project )
    {
		if( $task != null) { 
			$workDiaries = WorkDiary::with(['hasWorkDiaryItem' => function( $query ) use ($task){
				$query->where('task_id', $task);
			  }])->get();
		} else {
			$workDiaries = WorkDiary::with('hasWorkDiaryItem')->get();
		}
	
		if( $date != 'null') {
			$workDiaries = $workDiaries->filter(function ($workDiary, $key) use ($date) {
				return date('Y-m',strtotime( $workDiary->date)) == $date;
			});
		}
		
        if( $employee_id != null) {
            $workDiaries = $workDiaries->filter(function ($workDiary, $key) use ( $employee_id ) {
				/* dd($workDiary->project->employee_id); */
				$project_empl = $workDiary->project ? $workDiary->project->employee_id : null;
				$project_empl2 = $workDiary->project ? $workDiary->project->employee_id2 : null;
				
				return $workDiary->employee_id == $employee_id || $project_empl ==  $employee_id || $project_empl2 ==  $employee_id;
				/* || $workDiary->project ? $workDiary->project->employee_id == $employee_id : '' || $workDiary->project ? $workDiary->project->employee_id2 == $employee_id : '' */
            });
		}

		if( $project != null) {
            $workDiaries = $workDiaries->filter(function ($workDiary, $key) use ( $project ) {
				
                return $workDiary->project_id == $project;
            });
		}

        return $workDiaries;
	}
	
	public function getProjects ($workDiaries)
	{
		$employee =  Sentinel::getUser()->employee;
		
		$projects_array = array();
		foreach (array_keys($workDiaries->groupBy('project_id')->toArray()) as $workDiary_project) {
            array_push($projects_array, $workDiary_project );
		}
		
		$all_projects = Project::where('active',1)->get();
		$projects = $all_projects->filter(function ($project, $key) use ($projects_array, $employee) {
			return in_array($project->id, $projects_array) || $project->employee_id == $employee->id  || $project->employee_id2 == $employee->id;
		});

        return $projects;
	}

	public function getEmployees ($workDiaries)
	{
		$employees_array = array();
		foreach (array_keys($workDiaries->groupBy('employee_id')->toArray()) as $workDiary_project) {
            array_push($employees_array, $workDiary_project );
		}
		$all_employees = Employee::employees_lastNameASC();
		$employees = $all_employees->filter(function ($employee, $key) use ($employees_array) {
			return in_array($employee->id, $employees_array);
		});
		
        return $employees;
	}
	
	/***
	 * Vraća ukupne sekunde 
	*/
	public static function sumTask( $workDiary ) 
    {
		$seconds = 0;
		
		foreach ( $workDiary->hasWorkDiaryItem as $item ) {
			list($hour,$minute, $second) = explode(':', $item->time);
			$seconds += $hour*3600;
			$seconds += $minute*60;
			$seconds += $second;
		}
		/* 
		$hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60); */

		/* $time = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);  */
		
       return  $seconds;
	}
	
	/**
	 * vraća time HH:ii
	 */
    public static function sumTasks( $workDiaries ) 
    {
		$seconds = 0;
	
		foreach ( $workDiaries as $workDiary) {
			foreach ( $workDiary->hasWorkDiaryItem as $item ) {
				list($hour,$minute, $second) = explode(':', $item->time);
				$seconds += $hour*3600;
				$seconds += $minute*60;
				$seconds += $second;
			}
		}

		$hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);

		$time = sprintf('%02d:%02d:%02d', $hours, $mins, $secs); 
		
       return  $time;
    }
}