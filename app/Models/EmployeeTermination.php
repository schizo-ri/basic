<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTermination extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','termination_id','notice_period','check_out_date','comment'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 

	/*
	* The Eloquent termination model name
	* 
	* @var string
	*/
	protected static $terminationModel = 'App\Models\Termination'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}	
	
	/*
	* Returns the termination relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function termination()
	{
		return $this->belongsTo(static::$terminationModel,'termination_id');
	}	
	
	/*
	* Save EmployeeTermination
	* 
	* @param array $employee_termination
	* @return void
	*/
	
	public function saveEmployeeTermination($employee_termination=array())
	{
		return $this->fill($employee_termination)->save();
	}
	
	/*
	* Update EmployeeTermination
	* 
	* @param array $employee_termination
	* @return void
	*/
	
	public function updateEmployeeTermination($employee_termination=array())
	{
		return $this->update($employee_termination);
	}

	public static function EmployeeTerminationToday()
	{
		return EmployeeTermination::whereYear('check_out_date', date('Y'))->whereMonth('check_out_date', date('m'))->whereDay('check_out_date', date('d'))->get();
	}
}
