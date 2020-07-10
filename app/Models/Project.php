<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
    'name','project_no','duration','day_hours','saturday','start_date','end_date','categories','active','preparation_id'];

	/*
	* The Eloquent prepacation model name
	* 
	* @var string
	*/
	protected static $prepacationModel = 'App\Models\Prepacation'; 
	
	/*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function prepacation()
	{
		return $this->belongsTo(static::$prepacationModel,'preparation_id');
	}

    /*
	* Save Project
	* 
	* @param array $project
	* @return void
	*/
	public function saveProject($project=array())
	{
		return $this->fill($project)->save();
	}
	
	/*
	* Update Project
	* 
	* @param array $project
	* @return void
	*/
	
	public function updateProject($project=array())
	{
		return $this->update($project);
	}	
}
