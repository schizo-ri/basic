<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobInterview extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['date','first_name','last_name','oib', 'email','comment','phone','title','qualifications','work_id','years_service','salary','language','employee_id'];
	
	/*
	* The Eloquent works model name
	* 
	* @var string
	*/
	protected static $workModel = 'App\Models\Work'; 

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
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function work()
	{
		return $this->belongsTo(static::$workModel,'work_id');
	}

	/*
	* Save JobInterview
	* 
	* @param array $jobInterview
	* @return void
	*/
	public function saveJobInterview($jobInterview=array())
	{
		return $this->fill($jobInterview)->save();
	}
	
	/*
	* Update JobInterview
	* 
	* @param array $jobInterview
	* @return void
	*/
	
	public function updateJobInterview($jobInterview=array())
	{
		return $this->update($jobInterview);
	}	
}
