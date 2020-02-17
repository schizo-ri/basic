<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locco extends Model
{
    protected $fillable = ['car_id','date','employee_id','destination','start_km','end_km','distance','comment'];

    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent Cars model name
	* 
	* @var string
	*/
	protected static $carsModel = 'App\Models\Car'; 

	/*
	* Returns the Employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}
	
	/*
	* Returns the Cars relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function car()
	{
		return $this->belongsTo(static::$carsModel,'car_id');
	}
	
	/*
	* Save Locco
	* 
	* @param array $locco
	* @return void
	*/
	
	public function saveLocco ($locco=array())
	{
		return $this->fill($locco)->save();
	}
	
	/*
	* Update Locco
	* 
	* @param array $locco
	* @return void
	*/
	
	public function updateLocco($locco=array())
	{
		return $this->update($locco);
	}	
}
