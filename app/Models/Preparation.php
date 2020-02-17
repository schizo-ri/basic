<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preparation extends Model
{
    protected $fillable = [
        'project_no','name','project_manager','delivery','designed_by','preparation','mechanical_processing','marks_documentation','active'];

	/*
	* The Eloquent preparation model name
	* 
	* @var string
	*/
	protected static $userModel = 'App\User'; 
	
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
