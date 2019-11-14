<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
	
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'department_id','name','job_description','employee_id'
	];
	
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
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\Department'; 
	
	/*
	* Returns the department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function department()
	{
		return $this->belongsTo(static::$departmentModel,'department_id');
	}
	
	/*
	* Save Work
	* 
	* @param array $work
	* @return void
	*/
	public function saveWork($work=array())
	{
		return $this->fill($work)->save();
	}
	
	/*
	* Update Work
	* 
	* @param array $work
	* @return void
	*/
	
	public function updateWork($work=array())
	{
		return $this->update($work);
	}	
}
