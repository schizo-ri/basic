<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    protected $fillable = ['car_id', 'employee_id','liters','km','date'];
	 
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent Car model name
	* 
	* @var string
	*/
	protected static $carModel = 'App\Models\Car'; 
	
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
	* Returns the Car relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function car()
	{
		return $this->belongsTo(static::$carModel,'car_id');
	}
	
	/*
	* Save Fuel
	* 
	* @param array $fuel
	* @return void
	*/
	
	public function saveFuel ($fuel=array())
	{
		return $this->fill($fuel)->save();
	}
	
	/*
	* Update Fuel
	* 
	* @param array $fuel
	* @return void
	*/
	
	public function updateFuel($fuel=array())
	{
		return $this->update($fuel);
	}	
}
