<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetenceRating extends Model
{
    /**
     * The attributes thet are mass assignable
     *
     * @var array
     */
    protected $fillable = ['competence_id','description','descriptionUKR','rating'];

    /*
	* The Eloquent Competence model name
	* 
	* @var string
	*/
	protected static $competenceModel = 'App\Models\Competence'; 

    /*
	* The Eloquent CompetenceEvaluation model name
	* 
	* @var string
	*/
	protected static $evaluationModel = 'App\Models\CompetenceEvaluation'; 

    /*
	* Returns the Competence relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function competence()
	{
		return $this->belongsTo(static::$competenceModel,'competence_id');
	}

    /*
	* Returns the CompetenceEvaluation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasEvaluations()
	{
		return $this->hasMany(static::$evaluationModel,'rating_id');
    }	

    /*
	* Save CompetenceRating
	* 
	* @param array $competenceRating
	* @return void
	*/
	
	public function saveCompetenceRating ($competenceRating=array())
	{
		return $this->fill($competenceRating)->save();
	}
	
	/*
	* Update CompetenceRating
	* 
	* @param array $competenceRating
	* @return void
	*/
	
	public function updateCompetenceRating($competenceRating=array())
	{
		return $this->update($competenceRating);
	}
}
