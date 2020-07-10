<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelExpense extends Model
{
    protected $fillable = ['travel_id','bill','cost_description','amount','currency','total_amount'];
    
	/*
	* The Eloquent Travel model name
	* 
	* @var string
	*/
    protected static $travelModel = 'App\Models\TravelOrder'; 
    
    /*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function travel()
	{
		return $this->belongsTo(static::$travelModel,'travel_id');
    }
    
	/*
	* Save TravelExpense
	* 
	* @param array $travelExpense
	* @return void
	*/
	
	public function saveTravelExpense ($travelExpense=array())
	{
		return $this->fill($travelExpense)->save();
	}
	
	/*
	* Update TravelExpense
	* 
	* @param array $travelExpense
	* @return void
	*/
	
	public function updateTravelExpense($travelExpense=array())
	{
		return $this->update($travelExpense);
	}	
}
