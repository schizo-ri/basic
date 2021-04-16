<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
   /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['name','nameUKR','description','descriptionUKR','status','employee_id'];

	/*
	 * The Eloquent Employee model name
	 * 
	 * @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 

	/*
	 * Returns the Employee relationship
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}

    /*
	* The Eloquent CompetenceDepartment model name
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\CompetenceDepartment'; 

    /*
	* The Eloquent CompetenceGroupQuestion model name
	* 
	* @var string
	*/
	protected static $groupModel = 'App\Models\CompetenceGroupQuestion'; 

    /*
	* The Eloquent CompetenceRating model name
	* 
	* @var string
	*/
	protected static $ratingModel = 'App\Models\CompetenceRating'; 
  	
    /*
	* The Eloquent CompetenceEvaluation model name
	* 
	* @var string
	*/
	protected static $evaluationModel = 'App\Models\CompetenceEvaluation'; 

	/*
	* Returns the CompetenceDepartment relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasDepartments()
	{
		return $this->hasMany(static::$departmentModel,'competence_id');
    }

    /*
	* Returns the CompetenceGroupQuestion relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasGroups()
	{
		return $this->hasMany(static::$groupModel,'competence_id');
    }	

    /*
	* Returns the CompetenceRating relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasRatings()
	{
		return $this->hasMany(static::$ratingModel,'competence_id');
    }	

    /*
	* Returns the CompetenceEvaluations relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasEvaluations()
	{
		return $this->hasMany(static::$evaluationModel,'competence_id');
    }	

    /*
	* Save Competence
	* 
	* @param array $competence
	* @return void
	*/
	
	public function saveCompetence ($competence=array())
	{
		return $this->fill($competence)->save();
	}
	
	/*
	* Update Competence
	* 
	* @param array $competence
	* @return void
	*/
	
	public function updateCompetence($competence=array())
	{
		return $this->update($competence);
	}
}
