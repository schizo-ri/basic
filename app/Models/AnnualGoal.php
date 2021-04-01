<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualGoal extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['year','name','comment','end_date'];
	
	/*
	* Save AnnualGoal
	* 
	* @param array $annualGoal
	* @return void
	*/
	public function saveAnnualGoal($annualGoal=array())
	{
		return $this->fill($annualGoal)->save();
	}
	
	/*
	* Update AnnualGoal
	* 
	* @param array $annualGoal
	* @return void
	*/
	
	public function updateAnnualGoal($annualGoal=array())
	{
		return $this->update($annualGoal);
	}	
}
