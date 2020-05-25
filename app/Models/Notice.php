<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
	protected $fillable = ['employee_id','to_department','to_employee','title','notice','text_json','schedule_date'];
	
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
	* Save Notice
	* 
	* @param array $notice
	* @return void
	*/
	
	public function saveNotice ($notice=array())
	{
		return $this->fill($notice)->save();
	}
	
	/*
	* Update Notice
	* 
	* @param array $notice
	* @return void
	*/
	
	public function updateNotice($notice=array())
	{
		return $this->update($notice);
	}
}
