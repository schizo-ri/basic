<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationRating extends Model
{
	
	/**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['rating','name','question_id'];
	
	
	/*
	* The Eloquent EvaluationQuestion model name
	* 
	* @var string
	*/
	protected static $questionModel = 'App\Models\EvaluationQuestion'; 
		
	/*
	* Update EvaluationRating
	* 
	* @param array $evaluationRating
	* @return void
	*/
	
	public function updateEvaluationRating($evaluationRating=array())
	{
		return $this->update($evaluationRating);
	}	
}
