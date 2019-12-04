<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publish extends Model
{
    protected $fillable = [
        'project_id','employee_id','date'];

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
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $projectModel = 'App\Models\Project'; 
	
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
	* Save Publish
	* 
	* @param array $publish
	* @return void
	*/
	public function savePublish($publish=array())
	{
		return $this->fill($publish)->save();
	}
	
	/*
	* Update Publish
	* 
	* @param array $publish
	* @return void
	*/
	
	public function updatePublish($publish=array())
	{
		return $this->update($publish);
	}	
}
