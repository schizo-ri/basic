<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImprovementRecommendation extends Model
{
    protected $fillable = ['employee_id','comment','target_date','mentor'];

    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	
	protected static $employeeModel = 'App\Models\Employee'; 

    /*
	* Returns the Employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}

    /*
	* Returns the Employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function isMentor()
	{
		return $this->belongsTo(static::$employeeModel,'mentor');
	}

    /*
	* Save ImprovementRecommendation
	* 
	* @param array $improvementRecommendation
	* @return void
	*/
	
	public function saveImprovementRecommendation ($improvementRecommendation=array())
	{
		return $this->fill($improvementRecommendation)->save();
	}
	
	/*
	* Update ImprovementRecommendation
	* 
	* @param array $improvementRecommendation
	* @return void
	*/
	
	public function updateImprovementRecommendation($improvementRecommendation=array())
	{
		return $this->update($improvementRecommendation);
	}
}
