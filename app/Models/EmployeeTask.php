<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTask extends Model
{
    /**
        * The attributes thet are mass assignable
        *
        * @var array
	*/
    protected $fillable = ['employee_id','task_id','status','comment'];

      /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
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
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $taskModel = 'App\Models\Task'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function task()
	{
		return $this->belongsTo(static::$taskModel, 'task_id');
    }
    
    /*
	* Save EmployeeTask
	* 
	* @param array $employeeTask
	* @return void
	*/
	public function saveEmployeeTask($employeeTask=array())
	{
		return $this->fill($employeeTask)->save();
	}
	
	/*
	* Update EmployeeTask
	* 
	* @param array $employeeTask
	* @return void
	*/
	
	public function updateEmployeeTask($employeeTask=array())
	{
		return $this->update($employeeTask);
	}	
}
