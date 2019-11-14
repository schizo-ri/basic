<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInteres extends Model
{	
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'employee_id','category','description'
    ];
    
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
    }
    
    /*
	* Save UserInteres
	* 
	* @param array $userInteres
	* @return void
	*/
	public function saveUserInteres($userInteres=array())
	{
		return $this->fill($userInteres)->save();
	}
	
	/*
	* Update UserInteres
	* 
	* @param array $userInteres
	* @return void
	*/
	
	public function updateUserInteres($userInteres=array())
	{
		return $this->update($userInteres);
	}	
}
