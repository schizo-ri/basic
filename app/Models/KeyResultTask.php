<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyResultTask extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['keyresult_id','employee_id','name','comment','start_date','end_date','progress','comment_admin'];

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
	* The Eloquent KeyResultTask model name
	* 
	* @var string
	*/
	protected static $keyResultTasksCommentModel = 'App\Models\KeyResultTasksComment'; 	

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
	* Returns the employees relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function employee()
	{
		return $this->belongsTo(static::$employeesModel,'employee_id');
	}

	/*
	* Returns the keyResult relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasComments()
	{
		return $this->hasMany(static::$keyResultTasksCommentModel,'key_result_tasks_id');
    }	

    /*
	* Save KeyResultTask
	* 
	* @param array $keyResultTask
	* @return void
	*/
	public function saveKeyResultTask($keyResultTask=array())
	{
		return $this->fill($keyResultTask)->save();
	}
	
	/*
	* Update KeyResultTask
	* 
	* @param array $keyResultTask
	* @return void
	*/
	
	public function updateKeyResultTask($keyResultTask=array())
	{
		return $this->update($keyResultTask);
	}
}