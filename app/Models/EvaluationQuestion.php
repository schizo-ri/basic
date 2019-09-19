<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
	 /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	
	protected $fillable = ['category_id','name_question','description','description2','type'];
	
	/*
	* The Eloquent group model name
	* 
	* @var string
	*/
	protected static $groupModel = 'App\Models\EvaluationCategory'; 
	
	/*
	* Returns the group relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function group()
	{
		return $this->belongsTo(static::$groupModel,'category_id');
	}

	/*
	* Save EvaluatingQuestion
	* 
	* @param array $evaluatingQuestion
	* @return void
	*/
	public function saveEvaluatingQuestion($evaluatingQuestion=array())
	{
		return $this->fill($evaluatingQuestion)->save();
	}
	
	/*
	* Update EvaluatingQuestion
	* 
	* @param array $evaluatingQuestion
	* @return void
	*/
	
	public function updateEvaluatingQuestion($evaluatingQuestion=array())
	{
		return $this->update($evaluatingQuestion);
	}	
}
