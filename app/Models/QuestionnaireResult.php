<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireResult extends Model
{	
	/**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	
	protected $fillable = ['employee_id','question_id','answer_id','answer','questionnaire_id'];
    
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
	* The Eloquent Questionnaire model names
	* 
	* @var string
	*/
    protected static $questionnaireModel = 'App\Models\Questionnaire';
	
	/*
	* Returns the Questionnaire relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function questionnaireModel()
	{
		return $this->belongsTo(static::$questionnaireModel,'questionnaire_id');
    }
    
    /*
	* The Eloquent EvaluationQuestion model names
	* 
	* @var string
	*/
	protected static $questionModel = 'App\Models\EvaluationQuestion';
	
    /*
	* Returns the evaluationQuestion relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function question()
	{
		return $this->belongsTo(static::$questionModel,'question_id');
    }

    /*
	* The Eloquent EvaluationAnswer model names
	* 
	* @var string
	*/
    protected static $answerModel = 'App\Models\EvaluationAnswer';
    
    /*
	* Returns the EvaluationAnswer relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function answer()
	{
		return $this->belongsTo(static::$answerModel,'answer_id');
    }

	/*
	* Save QuestionnaireResult
	* 
	* @param array $questionnaireResult
	* @return void
	*/
	public function saveResults($questionnaireResult=array())
	{
		return $this->fill($questionnaireResult)->save();
	}
	
	/*
	* Update QuestionnaireResult
	* 
	* @param array $questionnaireResult
	* @return void
	*/
	
	public function updateResults($questionnaireResult=array())
	{
		return $this->update($questionnaireResult);
	}
}
