<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['employee_id','to_employee_id','car_id','type','task','start_date','end_date','interval_period','active','description'];

	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent EmployeeTasks model name
	* 
	* @var string
	*/
	protected static $employeeTasksModel = 'App\Models\EmployeeTask'; 
	

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
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	
	public function employeeTasks()
	{
		return $this->hasMany(static::$employeeTasksModel,'task_id');
	}
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function toEmployee()
	{
		return $this->belongsTo(static::$employeeModel,'to_employee_id');
	}
	
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $carModel = 'App\Models\Car'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function car()
	{
		return $this->belongsTo(static::$carModel,'car_id');
    }
    
	/*
	* Save Task
	* 
	* @param array $task
	* @return void
	*/
	
	public function saveTask ($task=array())
	{
		return $this->fill($task)->save();
	}
	
	/*
	* Update Task
	* 
	* @param array $task
	* @return void
	*/
	
	public function updateTask($task=array())
	{
		return $this->update($task);
	}	
}