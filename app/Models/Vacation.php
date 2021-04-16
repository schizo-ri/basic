<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'title','description','start_period','end_period','end_date','interval','no_week','plan','active'
	];
    
    /*
	* The Eloquent VacationPlan model name
	* 
	* @var string
	*/
	protected static $vacationPlanModel = 'App\Models\VacationPlan'; 

    /*
	* Returns the vacationPlan relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasPlans()
	{
		return $this->hasMany(static::$vacationPlanModel,'vacation_id');
	}


	/*
	* Save Vacation
	* 
	* @param array $vacation
	* @return void
	*/
	public function saveVacation($vacation=array())
	{
		return $this->fill($vacation)->save();
	}
	
	/*
	* Update Vacation
	* 
	* @param array $vacation
	* @return void
	*/
	
	public function updateVacation($vacation=array())
	{
		return $this->update($vacation);
	}	
}
