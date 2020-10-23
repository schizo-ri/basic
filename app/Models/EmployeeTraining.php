<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTraining extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['employee_id','training_id','date', 'expiry_date', 'description'];

	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee'; 	
	
	/*
	* Returns the employees relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function employee()
	{
		return $this->belongsTo(static::$employeesModel,'employee_id');
	}
	
	/*
	* The Eloquent training model name
	* 
	* @var string
	*/
	protected static $trainingModel = 'App\Models\Training'; 	
	
	/*
	* Returns the training relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function training()
	{
		return $this->belongsTo(static::$trainingModel,'training_id');
	}
	
	/*
	* Save EmployeeTraining
	* 
	* @param array $employeeTraining
	* @return void
	*/
	
	public function saveEmployeeTraining($employeeTraining=array())
	{
		return $this->fill($employeeTraining)->save();
	}
	
	/*
	* Update EmployeeTraining
	* 
	* @param array $employeeTraining
	* @return void
	*/
	
	public function updateEmployeeTraining($employeeTraining=array())
	{
		return $this->update($employeeTraining);
	}	

	public static function EmployeeTrainingDate( $date )
	{
		return EmployeeTraining::whereYear('expiry_date', date_format($date,'Y') )->whereMonth('expiry_date',  date_format($date,'m'))->whereDay('expiry_date', '=', date_format($date,'d'))->get();
	}	
}
