<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyResultTasksComment extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['key_result_tasks_id','employee_id','comment'];
 
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee'; 	

    /*
	* The Eloquent KeyResultTask model name
	* 
	* @var string
	*/
	protected static $keyResultTaskModel = 'App\Models\KeyResultTask'; 	

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
	* Returns the KeyResultTask relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function keyResultTask()
	{
		return $this->belongsTo(static::$keyResultTaskModel,'key_result_tasks_id');
	}

    /*
	* Save KeyResultTasksComment
	* 
	* @param array $keyResultTasksComment
	* @return void
	*/
	public function saveKeyResultTasksComment($keyResultTasksComment=array())
	{
		return $this->fill($keyResultTasksComment)->save();
	}
	
	/*
	* Update KeyResultTasksComment
	* 
	* @param array $keyResultTasksComment
	* @return void
	*/
	
	public function updateKeyResultTasksComment($keyResultTasksComment=array())
	{
		return $this->update($keyResultTasksComment);
	}	
}
