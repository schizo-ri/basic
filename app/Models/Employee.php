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
	* The Eloquent event model name
	* 
	* @var string
	*/
	protected static $eventModel = 'App\Models\Event'; 

	/*
	* The Eloquent task model name
	* 
	* @var string
	*/
	protected static $taskModel = 'App\Models\Task'; 

	/*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $loccoModel = 'App\Models\Locco'; 
	
	/*
	* The Eloquent TravelOrder model name
	* 
	* @var string
	*/
	protected static $travelModel = 'App\Models\TravelOrder'; 

	/*
	* The Eloquent WorkRecord model name
	* 
	* @var string
	*/
	protected static $workRecordModel = 'App\Models\WorkRecord'; 

	/*
	* The Eloquent works model name
	* 
	* @var string
	*/
	protected static $workModel = 'App\Models\Work'; 

	/*
	* The Eloquent works model name
	* 
	* @var string
	*/
	protected static $absenceModel = 'App\Models\Absence'; 

	/*
	* The Eloquent shortcut model name
	* 
	* @var string
	*/
	protected static $shortcutModel = 'App\Models\Shortcut'; 

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
	* Returns the Event relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasShortcuts()
	{
		return $this->hasMany(static::$shortcutModel,'employee_id');
	}

	/*
	* Returns the Event relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasEvents()
	{
		return $this->hasMany(static::$eventModel,'employee_id');
	}

	/*
	* Returns the Task relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasTasks()
	{
		return $this->hasMany(static::$taskModel,'employee_id');
	}

	/*
	* Returns the TravelOrder relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasTravels()
	{
		return $this->hasMany(static::$travelModel,'employee_id');
	}

	/*
	* Returns the TravelOrder relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasLocco()
	{
		return $this->hasMany(static::$loccoModel,'employee_id');
	}

	/*
	* Returns the TravelOrder relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasAbsences()
	{
		return $this->hasMany(static::$absenceModel,'employee_id');
	}

	/*
	* Returns the TravelOrder relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasWorkingRecord()
	{
		return $this->hasMany(static::$workRecordModel,'employee_id');
	}

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
