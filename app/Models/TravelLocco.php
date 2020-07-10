<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelLocco extends Model
{
    protected $fillable = ['travel_id','starting_point','destination', 'distance'];

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
	* Save TravelLocco
	* 
	* @param array $travelLocco
	* @return void
	*/
	
	public function saveTravelLocco ($travelLocco=array())
	{
		return $this->fill($travelLocco)->save();
	}
	
	/*
	* Update TravelLocco
	* 
	* @param array $travelLocco
	* @return void
	*/
	
	public function updateTravelLocco($travelLocco=array())
	{
		return $this->update($travelLocco);
    }	
    
}
