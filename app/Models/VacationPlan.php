<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationPlan extends Model
{
     /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'vacation_id','employee_id','start_date','request_id'
	];

    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
    
    /*
	* The Eloquent Vacation model name
	* 
	* @var string
	*/
	protected static $vacationModel = 'App\Models\Vacation'; 
	
    /*
	* The Eloquent Absence model name
	* 
	* @var string
	*/
	protected static $absenceModel = 'App\Models\Absence'; 
        
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
    }

	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function absence()
	{
		return $this->belongsTo(static::$absenceModel,'request_id');
    }

    /*
	* Returns the vacation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function vacation()
	{
		return $this->belongsTo(static::$vacationModel,'vacation_id');
    }

	/*
	* Save VacationPlan
	* 
	* @param array $vacationPlan
	* @return void
	*/
	public function saveVacationPlan($vacationPlan=array())
	{
		return $this->fill($vacationPlan)->save();
	}
	
	/*
	* Update VacationPlan
	* 
	* @param array $vacationPlan
	* @return void
	*/
	
	public function updateVacationPlan($vacationPlan=array())
	{
		return $this->update($vacationPlan);
	}	

}
