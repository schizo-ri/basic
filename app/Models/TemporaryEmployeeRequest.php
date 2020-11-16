<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryEmployeeRequest extends Model
{
    /* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['type','employee_id','start_date','end_date','start_time','end_time','comment','approve','approved_id','approved_date','approve_reason'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $absenceTypeModel = 'App\Models\AbsenceType'; 

	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee'; 	
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
    protected static $temporaryEmployeeModel = 'App\Models\TemporaryEmployee'; 	
	
	/*
	* Returns the authorized relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function absence_type()
	{
		return $this->belongsTo(static::$absenceTypeModel,'type');
	}

	/*
	* Returns the employees relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function employee()
	{
		return $this->belongsTo(static::$temporaryEmployeeModel,'employee_id');
	}
    
	/*
	* Returns the authorized relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function authorized()
	{
		return $this->belongsTo(static::$employeesModel,'approved_id');
	}
	
	/*
	* Save TemporaryEmployeeRequest
	* 
	* @param array $VacationRequest
	* @return void
	*/
	
	public function saveTemporaryEmployeeRequest($TemporaryEmployeeRequest=array())
	{
		return $this->fill($TemporaryEmployeeRequest)->save();
	}
	
	/*
	* Update TemporaryEmployeeRequest
	* 
	* @param array $TemporaryEmployeeRequest
	* @return void
	*/
	
	public function updateTemporaryEmployeeRequest($TemporaryEmployeeRequest=array())
	{
		return $this->update($TemporaryEmployeeRequest);
	}

	public static function TemporaryEmployeeSortApproved () {
		return TemporaryEmployeeRequest::where('approve',1)->orderBy('temporary_employee_requests.type','ASC')->get();
	}
}
