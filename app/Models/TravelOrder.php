<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    protected $fillable = ['date','employee_id','car_id','destination','days','start_date','end_date','advance','advance_date','rest_payout','calculate_employee','locco_id'];
    
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}
    
    public function calculatedBy()
	{
		return $this->belongsTo(static::$employeeModel,'calculate_employee');
    }
    
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $carModel = 'App\Models\Car'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function car()
	{
		return $this->belongsTo(static::$carModel,'car_id');
    }
    
	/*
	* Save TravelOrder
	* 
	* @param array $travelOrder
	* @return void
	*/
	
	public function saveTravelOrder ($travelOrder=array())
	{
		return $this->fill($travelOrder)->save();
	}
	
	/*
	* Update TravelOrder
	* 
	* @param array $travelOrder
	* @return void
	*/
	
	public function updateTravelOrder($travelOrder=array())
	{
		return $this->update($travelOrder);
	}	

}
