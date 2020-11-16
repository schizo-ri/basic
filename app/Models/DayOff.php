<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayOff extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['employee_id','comment','days_no','user_id'];

     /*
	* The Eloquent user model name
	* 
	* @var string
	*/
    protected static $employeeModel = 'App\Models\Employee'; 
    
    /*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
    }
    
    /*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function addEmployee()
	{
		return $this->belongsTo(static::$employeeModel,'user_id');
    }

    /*
        * Save DayOff
        * 
        * @param array $dayOff
        * @return void
	*/
	
	public function saveDayOff ($dayOff=array())
	{
		return $this->fill($dayOff)->save();
	}
	
	/*
	* Update DayOff
	* 
	* @param array $dayOff
	* @return void
	*/
	
	public function updateDayOff($dayOff=array())
	{
		return $this->update($dayOff);
	}	
}
