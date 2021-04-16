<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectWorkTask extends Model
{
    protected $fillable = ['project_id','task_id','hours'];

    /*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $projectModel = 'App\Models\Project'; 	

    /*
	* The Eloquent WorkTask model name
	* 
	* @var string
	*/
	protected static $workTaskModel = 'App\Models\WorkTask'; 	

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
	* Returns the project relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function task()
	{
		return $this->belongsTo(static::$workTaskModel,'task_id');
	}

    /*
	* Save ProjectWorkTask
	* 
	* @param array $projectWorkTask
	* @return void
	*/
	
	public function saveProjectWorkTask($projectWorkTask=array())
	{
		return $this->fill($projectWorkTask)->save();
	}
	
	/*
	* Update ProjectWorkTask
	* 
	* @param array $projectWorkTask
	* @return void
	*/
	
	public function updateProjectWorkTask($projectWorkTask=array())
	{
		return $this->update($projectWorkTask);
	}
}
