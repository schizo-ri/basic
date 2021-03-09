<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetenceGroupGroup extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['name'];

    /*
	* Save CompetenceGroup
	* 
	* @param array $competenceGroup
	* @return void
	*/
	public function saveCompetenceGroup($competenceGroup=array())
	{
		return $this->fill($competenceGroup)->save();
	}
	
	/*
	* Update CompetenceGroup
	* 
	* @param array $competenceGroup
	* @return void
	*/
	
	public function updateCompetenceGroup($competenceGroup=array())
	{
		return $this->update($competenceGroup);
    }
}
