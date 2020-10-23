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
		'user_id','father_name','mather_name','maiden_name','oib','oi','oi_expiry','b_day','b_place','mobile','email','priv_mobile','priv_email','prebiv_adresa','prebiv_grad','borav_adresa','borav_grad','title','qualifications','marital','work_id','superior_id','reg_date','probation','years_service','termination_service','first_job','comment','checkout','termination_id',
		'effective_cost','brutto','color','abs_days','lijecn_pregled','znr','size','shoe_size','days_off','stranger','permission_date'

	];
	
	/*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $userModel = 'App\User'; 
	
	/*
	* The Eloquent EmployeeDepartment model name
	* 
	* @var string
	*/
	protected static $employeeDepartmentModel = 'App\Models\EmployeeDepartment'; 

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
	* The Eloquent locco model name
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
	* The Eloquent kid model name
	* 
	* @var string
	*/
	protected static $kidModel = 'App\Models\Kid'; 

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
	
	public function hasKids()
	{
		return $this->hasMany(static::$kidModel,'employee_id');
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
	* Returns the employeeDepartment relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function hasEmployeeDepartmen()
	{
		return $this->hasMany(static::$employeeDepartmentModel,'employee_id');
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

	/*
	* get emails from Employee
	* 
	* @return void
	*/
	public static function getEmails()
	{
		return Employee::where('id','<>',0)->where('checkout',null)->where('user_id','<>',null)->get()->pluck('email')->toArray();
	}

	/*
	* get employees join users order by firstName ASC from Employee
	* 
	* @return void
	*/
	public static function employees_firstNameASC()
	{
		return Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->where('employees.id','<>',0)->where('employees.checkout',null)->where('employees.user_id','<>',null)->orderBy('users.first_name','ASC')->get();
	}

	/*
	* get employees join users order by lastName ASC from Employee
	* 
	* @return void
	*/
	public static function employees_lastNameASC()
	{
		return Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->where('employees.id','<>',0)->where('employees.checkout',null)->where('employees.user_id','<>',null)->orderBy('users.last_name','ASC')->get();
	}

	public static function employeesAnniversary( $date )
	{
		return Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->where('employees.id','<>',0)->where('employees.checkout',null)->where('employees.user_id','<>',null)->whereMonth('employees.reg_date', date_format($date,'m'))->whereDay('employees.reg_date', date_format($date,'d'))->orderBy('users.last_name','ASC')->get();
	}

	public static function employeesBday( $date )
	{
		return Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->where('employees.id','<>',0)->where('employees.checkout',null)->where('employees.user_id','<>',null)->whereMonth('employees.b_day', date_format($date,'m'))->whereDay('employees.b_day', date_format($date,'d'))->orderBy('users.last_name','ASC')->get();
	}

	public static function employeesProbation( $date )
	{
		return Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->where('employees.id','<>',0)->where('employees.checkout',null)->where('employees.user_id','<>',null)->whereYear('employees.reg_date', date_format($date,'Y'))->whereMonth('employees.reg_date', date_format($date,'m'))->whereDay('employees.reg_date', date_format($date,'d'))->orderBy('users.last_name','ASC')->get();
	}

	public static function employeesMedicalExamination( $date )
	{
		return Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->where('employees.id','<>',0)->where('employees.checkout',null)->where('employees.user_id','<>',null)->whereYear('employees.lijecn_pregled', date_format($date,'Y'))->whereMonth('employees.lijecn_pregled', date_format($date,'m'))->whereDay('employees.lijecn_pregled', date_format($date,'d'))->orderBy('users.last_name','ASC')->get();
	}

	public static function employeeStranger( $date )
	{
		return Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->where('employees.id','<>',0)->where('employees.checkout',null)->where('employees.user_id','<>',null)->whereYear('employees.permission_date', date_format($date,'Y'))->whereMonth('employees.permission_date', date_format($date,'m'))->whereDay('employees.permission_date', date_format($date,'d'))->orderBy('users.last_name','ASC')->get();
	}
}