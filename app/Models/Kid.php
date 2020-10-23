<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kid extends Model
{
     /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['first_name','last_name','employee_id','b_day'];
	

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
	* Save Kid
	* 
	* @param array $kid
	* @return void
	*/
	
	public function saveKid($kid=array())
	{
		return $this->fill($kid)->save();
	}
	
	/*
	* Update Kid
	* 
	* @param array $kid
	* @return void
	*/
	
	public function updateKid($kid=array())
	{
		return $this->update($kid);
	}	
}
