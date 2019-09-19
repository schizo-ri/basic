<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['employee_id','type','title','date','time1','time2','description'];
	
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
	* Save Event
	* 
	* @param array $event
	* @return void
	*/
	
	public function saveEvent ($event=array())
	{
		return $this->fill($event)->save();
	}
	
	/*
	* Update Event
	* 
	* @param array $event
	* @return void
	*/
	
	public function updateEvent($event=array())
	{
		return $this->update($event);
	}	
}
