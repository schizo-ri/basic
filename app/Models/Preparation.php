<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preparation extends Model
{
    protected $fillable = [
        'project_no','name','project_manager','delivery','designed_by','preparation','mechanical_processing','marks_documentation','delivered','active'];

	/*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $userModel = 'App\User'; 
	
	/*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $projectModel = 'App\Project'; 

	/*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $equipmentModel = 'App\Models\EquipmentList'; 

	/*
	* Returns the equipmentList relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasmany
	*/
	
	public function equipment()
	{
		return $this->hasMany(static::$equipmentModel,'preparation_id');
	}

	/*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function manager()
	{
		return $this->belongsTo(static::$userModel,'project_manager');
	}

	/*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function designed()
	{
		return $this->belongsTo(static::$userModel,'designed_by');
	}

	/*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function project()
	{
		return $this->hasOne(static::$projectModel,'preparation_id');
	}

    /*
	* Save Preparation
	* 
	* @param array $preparation
	* @return void
	*/
	public function savePreparation($preparation=array())
	{
		return $this->fill($preparation)->save();
	}
	
	/*
	* Update Preparation
	* 
	* @param array $preparation
	* @return void
	*/
	
	public function updatePreparation($preparation=array())
	{
		return $this->update($preparation);
    }
}
