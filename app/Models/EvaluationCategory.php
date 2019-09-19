<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationCategory extends Model
{
	 /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	
	protected $fillable = ['name_category','coefficient','questionnaire_id'];
	
	/*
	* The Eloquent group model name
	* 
	* @var string
	*/
	protected static $questionnaireModel = 'App\Models\Questionnaire'; 
	
	/*
	* Returns the group relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function questionnaire()
	{
		return $this->belongsTo(static::$questionnaireModel,'questionnaire_id');
	}
	
	/*
	* Save EvaluationCategory
	* 
	* @param array $evaluationCategory
	* @return void
	*/
	public function saveCategory($evaluationCategory=array())
	{
		return $this->fill($evaluationCategory)->save();
	}
	
	/*
	* Update EvaluationCategory
	* 
	* @param array $evaluationCategory
	* @return void
	*/
	
	public function updateCategory($evaluationCategory=array())
	{
		return $this->update($evaluationCategory);
	}	
}
