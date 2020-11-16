<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emailing extends Model
{	
	protected $fillable = ['model','method','sent_to_dep','sent_to_empl'];
	 
	 /*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $departmentsModel = 'App\Models\Department'; 
	
	/*
	* The Eloquent table model name
	* 
	* @var string
	*/
	protected static $tableModel = 'App\Models\Table'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'sent_to_empl');
	}
	
	/*
	* Returns the department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function department()
	{
		return $this->belongsTo(static::$departmentsModel,'sent_to_dep');
	}	
	
	/*
	* Returns the table relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function table()
	{
		return $this->belongsTo(static::$tableModel,'model');
	}	
	
	/*
	* Save Emailing
	* 
	* @param array $emailing
	* @return void
	*/
	
	public function saveEmailing ($emailing=array())
	{
		return $this->fill($emailing)->save();
	}
	
	/*
	* Update Emailing
	* 
	* @param array $emailing
	* @return void
	*/
	
	public function updateEmailing($emailing=array())
	{
		return $this->update($emailing);
	}	
	
}
