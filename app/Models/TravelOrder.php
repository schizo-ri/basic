<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    protected $fillable = ['date','employee_id','car_id','destination','description','days','start_date','end_date','advance','advance_date','rest_payout','calculate_employee','locco_id','daily_wage','status'];
    
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
	* The Eloquent locco model name
	* 
	* @var string
	*/
	protected static $loccoModel = 'App\Models\Locco'; 

	/*
	* The Eloquent TravelExpense model name
	* 
	* @var string
	*/
	protected static $expenseModel = 'App\Models\TravelExpense'; 
	
	/*
	* The Eloquent TravelLocco model name
	* 
	* @var string
	*/
	protected static $loccoTravelModel = 'App\Models\TravelLocco'; 

	/*
	* Returns the car relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function car()
	{
		return $this->belongsTo(static::$carModel,'car_id');
	}
	
	/*
	* Returns the locco relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function locco()
	{
		return $this->belongsTo(static::$loccoModel,'locco_id');
    }

	/*
	* Returns the TravelLocco relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	
	public function expenses()
	{
		return $this->hasMany(static::$expenseModel,'travel_id');
	}

	/*
	* Returns the TravelExpense relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	
	public function loccos()
	{
		return $this->hasMany(static::$loccoTravelModel,'travel_id');
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