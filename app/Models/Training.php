<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['name','description','institution'];

    /*
	* The Eloquent EmployeeTraining model name
	* 
	* @var string
	*/
    protected static $employeeTrainingModel = 'App\Models\EmployeeTraining'; 
    
	/*
	* Returns the EmployeeTraining relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasEmployeeTrainings()
	{
		return $this->hasMany(static::$employeeTrainingModel,'training_id');
    }
    
	/*
	* Save Training
	* 
	* @param array $training
	* @return void
	*/
	
	public function saveTraining($training=array())
	{
		return $this->fill($training)->save();
	}
	
	/*
	* Update Training
	* 
	* @param array $training
	* @return void
	*/
	
	public function updateTraining($training=array())
	{
		return $this->update($training);
	}
}
