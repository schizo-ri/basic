<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['car_index','manufacturer','model','registration','chassis','first_registration','last_registration','current_km','last_service','department_id','employee_id','enc','private_car'];

    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employee_Model = 'App\Models\Employee'; 
	
	/*
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $departmentsModel = 'App\Models\Department'; 
	
	
	/*
	* The Eloquent locco model name
	* 
	* @var string
	*/
	protected static $loccoModel = 'App\Models\Locco'; 
	
	/*
	* Returns the locco relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function locco()
	{
		return $this->hasMany(static::$loccoModel,'car_id')->orderBy('created_at','DESC');
    }	
    
	/*
	* Returns the Users relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employee_Model,'employee_id');
	}
	
	/*
	* Returns the department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function department()
	{
		return $this->belongsTo(static::$departmentsModel,'department_id');
    }

    /*
	* Save Car
	* 
	* @param array $car
	* @return void
	*/
	
	public function saveCar ($car=array())
	{
		return $this->fill($car)->save();
	}
	
	/*
	* Update Car
	* 
	* @param array $car
	* @return void
	*/
	
	public function updateCar($car=array())
	{
		return $this->update($car);
	}	
}