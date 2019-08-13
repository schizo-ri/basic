<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['user_id','employee_id','date','questionnaire_id','category_id','question_id','koef','rating'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; /*
	
	* The Eloquent questionnaire model name
	* 
	* @var string
	*/
	protected static $questionnaireModel = 'App\Models\Questionnaire'; 
	
	/*
	* The Eloquent group model name
	* 
	* @var string
	*/
	protected static $categoryModel = 'App\Models\EvaluationCategory'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function user()
	{
		return $this->belongsTo(static::$employeeModel,'user_id');
	}
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function questionnaire()
	{
		return $this->belongsTo(static::$questionnaireModel,'questionnaire_id');
	}/*
	
	
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}
	
	/*
	* Returns the group relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function category()
	{
		return $this->belongsTo(static::$groupModel,'category_id');
	}
	
	/*
	* The Eloquent EvaluatingQuestion model name
	* 
	* @var string
	*/
	protected static $questionModel = 'App\Models\EvaluationQuestion'; 
	
	/*
	* Returns the evaluatingQuestion relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function question()
	{
		return $this->belongsTo(static::$questionModel,'question_id');
	}

	/*
	* Save Evaluation
	* 
	* @param array $Evaluation
	* @return void
	*/
	public function saveEvaluation($evaluation=array())
	{
		return $this->fill($evaluation)->save();
	}
	
	/*
	* Update Evaluation
	* 
	* @param array $evaluation
	* @return void
	*/
	
	public function updateEvaluation($evaluation=array())
	{
		return $this->update($evaluation);
	}	
}
