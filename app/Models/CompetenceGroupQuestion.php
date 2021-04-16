<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetenceGroupQuestion extends Model
{
    /**
     * The attributes thet are mass assignable
     *
     * @var array
     */
    protected $fillable = ['competence_id','name','nameUKR','description','descriptionUKR','coefficient'];

    /*
	* The Eloquent Competence model name
	* 
	* @var string
	*/
	protected static $competenceModel = 'App\Models\Competence'; 

    /*
	* The Eloquent CompetenceQuestion model name
	* 
	* @var string
	*/
	protected static $questionModel = 'App\Models\CompetenceQuestion'; 

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
	* Returns the CompetenceQuestion relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasQuestions()
	{
		return $this->hasMany(static::$questionModel,'group_id');
    }	

    /*
	* Save CompetenceGroupQuestion
	* 
	* @param array $competenceGroupQuestion
	* @return void
	*/
	
	public function saveCompetenceGroupQuestion ($competenceGroupQuestion=array())
	{
		return $this->fill($competenceGroupQuestion)->save();
	}
	
	/*
	* Update CompetenceGroupQuestion
	* 
	* @param array $competenceGroupQuestion
	* @return void
	*/
	
	public function updateCompetenceGroupQuestion($competenceGroupQuestion=array())
	{
		return $this->update($competenceGroupQuestion);
	}
}
