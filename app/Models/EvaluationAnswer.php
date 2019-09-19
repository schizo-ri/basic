<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationAnswer extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	
    protected $fillable = ['question_id','answer'];
    
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
	* Save EvaluationAnswer
	* 
	* @param array $evaluationAnswer
	* @return void
	*/
	public function saveEvaluationAnswer($evaluationAnswer=array())
	{
		return $this->fill($evaluationAnswer)->save();
	}
	
	/*
	* Update EvaluationAnswer
	* 
	* @param array $evaluationAnswer
	* @return void
	*/
	
	public function updateEvaluationAnswer($evaluationAnswer=array())
	{
		return $this->update($evaluationAnswer);
    }
    
}
