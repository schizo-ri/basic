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
	protected $fillable = ['rating','name'];
	
	/*
	* Save EvaluationRating
	* 
	* @param array $evaluationRating
	* @return void
	*/
	public function saveEvaluationRating($evaluationRating=array())
	{
		return $this->fill($evaluationRating)->save();
	}
	
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
