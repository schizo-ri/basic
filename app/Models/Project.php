<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['id','erp_id','customer_oib','investitor_id','customer_id','name','object','employee_id','employee_id2','active'];
	
	/*
	* The Eloquent customer model name
	* 
	* @var string
	*/
	protected static $customerModel = 'App\Models\Customer';
	
	/*
	* The Eloquent afterhour model name
	* 
	* @var string
	*/
	protected static $afterhourModel = 'App\Models\Afterhour';

	/*
	* The Eloquent investitor model name
	* 
	* @var string
	*/
	protected static $investitorModel = 'App\Models\Customer';
	
	/*
	* The Eloquent locco model name
	* 
	* @var string
	*/
	protected static $loccoModel = 'App\Models\Locco'; 
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 	
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $diaryModel = 'App\Models\WorkDiary'; 	

	/*
	* Returns the locco relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function locco()
	{
		return $this->hasMany(static::$loccoModel,'project_id')->orderBy('created_at','DESC');
	}	
	
	/*
	* Returns the locco relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasDiary()
	{
		return $this->hasMany(static::$diaryModel,'project_id')->orderBy('date','DESC');
	}	

	/*
	* Returns the locco relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function afterhour()
	{
		return $this->hasMany(static::$afterhourModel,'project_id')->orderBy('created_at','DESC');
	}	
	
	/*
	* Returns the customer relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function customer()
	{
		return $this->belongsTo(static::$customerModel,'customer_id');
	}
	
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
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee2()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id2');
	}

	/*
	* Returns the investitor relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function investitor()
	{
		return $this->belongsTo(static::$investitorModel,'investitor_id');
	}
	
	/*
	* Save Project
	* 
	* @param array $project
	* @return void
	*/
	
	public function saveProject($project=array())
	{
		return $this->fill($project)->save();
	}
	
	/*
	* Update Project
	* 
	* @param array $project
	* @return void
	*/
	
	public function updateProject($project=array())
	{
		return $this->update($project);
	}

	
}
