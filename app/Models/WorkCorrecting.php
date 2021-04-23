<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkCorrecting extends Model
{
   /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'date','project_id','user_id','employee_id','time','comment','approve','approved_id','approved_date','approve_h','approved_reason'
	];

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
	
	public function user()
	{
		return $this->belongsTo(static::$employeeModel,'user_id');
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
	* Save WorkCorrecting
	* 
	* @param array $workCorrecting
	* @return void
	*/
	public function saveWorkCorrecting($workCorrecting=array())
	{
		return $this->fill($workCorrecting)->save();
	}
	
	/*
	* Update WorkCorrecting
	* 
	* @param array $workCorrecting
	* @return void
	*/
	
	public function updateWorkCorrecting($workCorrecting=array())
	{
		return $this->update($workCorrecting);
	}	
}
