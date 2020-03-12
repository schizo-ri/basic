<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicalService extends Model
{
    protected $fillable = ['car_id', 'employee_id','comment','price','km','date'];
	 
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
	* Save VehicalService
	* 
	* @param array $vehicalService
	* @return void
	*/
	
	public function saveVehicalService ($vehicalService=array())
	{
		return $this->fill($vehicalService)->save();
	}
	
	/*
	* Update VehicalService
	* 
	* @param array $vehicalService
	* @return void
	*/
	
	public function updateVehicalService($vehicalService=array())
	{
		return $this->update($vehicalService);
	}	
}
