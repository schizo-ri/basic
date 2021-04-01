<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyResult extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['okr_id','employee_id','name','comment','start_date','end_date','progress'];

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
	* The Eloquent Okr model name
	* 
	* @var string
	*/
	protected static $okrModel = 'App\Models\Okr'; 	

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
	public function okr()
	{
		return $this->belongsTo(static::$okrModel,'okr_id');
	}

	/*
	* Returns the keyResult relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasTasks()
	{
		return $this->hasMany(static::$keyResultTaskModel,'keyresult_id');
    }	

    /*
	* Save KeyResult
	* 
	* @param array $keyResult
	* @return void
	*/
	public function saveKeyResult($keyResult=array())
	{
		return $this->fill($keyResult)->save();
	}
	
	/*
	* Update KeyResult
	* 
	* @param array $keyResult
	* @return void
	*/
	
	public function updateKeyResult($keyResult=array())
	{
		return $this->update($keyResult);
	}	
}
