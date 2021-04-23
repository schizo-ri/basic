<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OkrComment extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['okr_id','employee_id','comment'];
	
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee'; 	

    /*
	* The Eloquent Okr model name
	* 
	* @var string
	*/
	protected static $okrModel = 'App\Models\Okr'; 	

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
	* Returns the employees relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function okr()
	{
		return $this->belongsTo(static::$okrModel,'okr_id');
	}

    /*
	* Save OkrComment
	* 
	* @param array $okrComment
	* @return void
	*/
	public function saveOkrComment($okrComment=array())
	{
		return $this->fill($okrComment)->save();
	}
	
	/*
	* Update OkrComment
	* 
	* @param array $okrComment
	* @return void
	*/
	
	public function updateOkrComment($okrComment=array())
	{
		return $this->update($okrComment);
	}	
}
