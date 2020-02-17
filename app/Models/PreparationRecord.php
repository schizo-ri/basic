<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparationRecord extends Model
{
    protected $fillable = [
        'preparation_id','preparation','mechanical_processing','marks_documentation','date'];

    
    /*
	* The Eloquent preparation model name
	* 
	* @var string
	*/
	protected static $preparationModel = 'App\Models\Preparation'; 
	
	/*
	* Returns the preparation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function preparation1()
	{
		return $this->belongsTo(static::$preparationModel,'preparation_id');
	}
    
    /*
	* Save PreparationRecord
	* 
	* @param array $preparationRecord
	* @return void
	*/
	public function savePreparationRecord($preparationRecord=array())
	{
		return $this->fill($preparationRecord)->save();
	}
	
	/*
	* Update PreparationRecord
	* 
	* @param array $preparationRecord
	* @return void
	*/
	
	public function updatePreparationRecord($preparationRecord=array())
	{
		return $this->update($preparationRecord);
    }
}
