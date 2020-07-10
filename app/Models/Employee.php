<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
	
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'user_id','father_name','mather_name','oib','oi','oi_expiry','b_day','b_place','mobile','email','priv_mobile','priv_email','prebiv_adresa','prebiv_grad','borav_adresa','borav_grad','title','qualification s','marital','work_id','superior_id','reg_date','probation','years_service','termination_service','first_job','comment','checkout',
		'effective_cost','brutto','color','abs_days'];
	
	/*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $userModel = 'App\User'; 
	
	/*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $travelModel = 'App\Models\TravelOrder'; 

	/*
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function user()
	{
		return $this->belongsTo(static::$userModel,'user_id');
	}
	
	/*
	* Returns the TravelOrder relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	
	public function hasTravels()
	{
		return $this->hasMany(static::$travelModel,'employee_id');
	}

	/*
	* The Eloquent works model name
	* 
	* @var string
	*/
	protected static $workModel = 'App\Models\Work'; 
	
	/*
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function work()
	{
		return $this->belongsTo(static::$workModel,'work_id');
	}
	
	/*
	* Save Employee
	* 
	* @param array $employee
	* @return void
	*/
	public function saveEmployee($employee=array())
	{
		return $this->fill($employee)->save();
	}
	
	/*
	* Update Employee
	* 
	* @param array $employee
	* @return void
	*/
	
	public function updateEmployee($employee=array())
	{
		return $this->update($employee);
	}	
}
