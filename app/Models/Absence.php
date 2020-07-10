<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
	
	/* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['type','employee_id','start_date','end_date','start_time','end_time','comment','approve','approve_reason','approved_id','approved_date','decree'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee'; 	
	
	/*
	* Returns the employees relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function employee()
	{
		return $this->belongsTo(static::$employeesModel,'employee_id');
	}
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $absenceTypeModel = 'App\Models\AbsenceType'; 
	
	/*
	* Returns the authorized relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function approved()
	{
		return $this->belongsTo(static::$employeesModel,'approved_id');
	}
	
	/*
	* Save Absence
	* 
	* @param array $absence
	* @return void
	*/
	
	/*
	* Returns the authorized relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function absence()
	{
		return $this->belongsTo(static::$absenceTypeModel,'type');
	}
	
	public function saveAbsence($absence=array())
	{
		return $this->fill($absence)->save();
	}
	
	/*
	* Update Absence
	* 
	* @param array $absence
	* @return void
	*/
	
	public function updateAbsence($absence=array())
	{
		return $this->update($absence);
	}	
}
