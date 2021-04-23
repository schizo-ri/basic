<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyResultsComment extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['key_results_id','employee_id','comment'];

	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee'; 	

    /*
	* The Eloquent keyResult model name
	* 
	* @var string
	*/
	protected static $keyResultModel = 'App\Models\KeyResult';

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
	* Returns the KeyResult relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function keyResult()
	{
		return $this->belongsTo(static::$keyResultModel,'keyresult_id');
	}

    /*
	* Save KeyResultsComment
	* 
	* @param array $keyResultsComment
	* @return void
	*/
	public function saveKeyResultsComment($keyResultsComment=array())
	{
		return $this->fill($keyResultsComment)->save();
	}
	
	/*
	* Update KeyResultsComment
	* 
	* @param array $keyResultsComment
	* @return void
	*/
	
	public function updateKeyResultsComment($keyResultsComment=array())
	{
		return $this->update($keyResultsComment);
	}	
}
