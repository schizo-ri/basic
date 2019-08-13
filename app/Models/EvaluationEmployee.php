<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationEmployee extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

	 /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','ev_employee_id','mm_yy','questionnaire_id','status'];

	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent Questionnaire model name
	* 
	* @var string
	*/
	protected static $questionnaireModel = 'App\Models\Questionnaire'; 
	
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
	* Returns the Questionnaire relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function questionnaire()
	{
		return $this->belongsTo(static::$questionnaireModel,'questionnaire_id');
	}
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	public function evaleated_employee()
	{
		return $this->belongsTo(static::$employeeModel,'ev_employee_id');
	}

	/*
	* Save EvaluationEmployee
	* 
	* @param array $evaluatingEmployee
	* @return void
	*/
	public function saveEvaluationEmployee($evaluatingEmployee=array())
	{
		return $this->fill($evaluatingEmployee)->save();
	}
	
	/*
	* Update EvaluationEmployee
	* 
	* @param array $evaluatingEmployee
	* @return void
	*/
	
	public function updateEvaluationEmployee($evaluatingEmployee=array())
	{
		return $this->update($evaluatingEmployee);
	}	
}
