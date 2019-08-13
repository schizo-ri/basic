<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	
	protected $fillable = ['name','description','status'];
	
	/*
	* Save Questionnaries
	* 
	* @param array $questionnaire
	* @return void
	*/
	public function saveQuestionnaire($questionnaire=array())
	{
		return $this->fill($questionnaire)->save();
	}
	
	/*
	* Update Questionnaire
	* 
	* @param array $questionnaire
	* @return void
	*/
	
	public function updateQuestionnaire($questionnaire=array())
	{
		return $this->update($questionnaire);
	}
}
