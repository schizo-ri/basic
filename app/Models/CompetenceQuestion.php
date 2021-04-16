<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetenceQuestion extends Model
{
    /**
     * The attributes thet are mass assignable
     *
     * @var array
     */
    protected $fillable = ['group_id','name','nameUKR','description','descriptionUKR','rating'];

     /*
	* The Eloquent CompetenceGroupQuestion model name
	* 
	* @var string
	*/
	protected static $groupModel = 'App\Models\CompetenceGroupQuestion'; 

    /*
	* The Eloquent CompetenceEvaluation model name
	* 
	* @var string
	*/
	protected static $evaluationModel = 'App\Models\CompetenceEvaluation'; 

    /*
	* Returns the CompetenceGroupQuestion relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function group()
	{
		return $this->belongsTo(static::$groupModel,'group_id');
	}

    /*
	* Returns the CompetenceEvaluation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasEvaluations()
	{
		return $this->hasMany(static::$evaluationModel,'question_id');
    }	

    /*
	* Save CompetenceQuestion
	* 
	* @param array $competenceQuestion
	* @return void
	*/
	
	public function saveCompetenceQuestion ($competenceQuestion=array())
	{
		return $this->fill($competenceQuestion)->save();
	}
	
	/*
	* Update CompetenceQuestion
	* 
	* @param array $competenceQuestion
	* @return void
	*/
	
	public function updateCompetenceQuestion($competenceQuestion=array())
	{
		return $this->update($competenceQuestion);
	}
}
