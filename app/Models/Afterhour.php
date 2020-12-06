<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Afterhour extends Model
{ 
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','project_id','erp_task_id','date','start_time', 'end_time', 'comment','approve','approved_id','approved_date','approve_h','paid','approved_reason'];
	
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
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function project()
	{
		return $this->belongsTo(static::$projectModel,'project_id');
	}
	

	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function approved()
	{
		return $this->belongsTo(static::$employeeModel,'approved_id');
	}
	
	/*
	* Save Afterhour
	* 
	* @param array $afterhour
	* @return void
	*/
	public function saveAfterhour($afterhour=array())
	{
		return $this->fill($afterhour)->save();
	}
	
	/*
	* Update Afterhour
	* 
	* @param array $afterhour
	* @return void
	*/
	
	public function updateAfterhour($afterhour=array())
	{
		return $this->update($afterhour);
	}	
}
