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
	protected $fillable = ['name','type','group_id'];

    /*
	* The Eloquent CompetenceGroup model name
	* 
	* @var string
	*/
	protected static $competenceGroupModel = 'App\Models\CompetenceGroup'; 

    /*
	* Returns the CompetenceGroup relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function competenceGroup()
	{
		return $this->belongsTo(static::$competenceGroupModel,'group_id');
	}

    /*
	* Save Competence
	* 
	* @param array $competence
	* @return void
	*/
	public function saveCompetence($competence=array())
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
