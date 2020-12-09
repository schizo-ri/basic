<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyLocation extends Model
{
    protected $fillable = ['name','address','city','phone','comment'];

    /*
	* Save EnergyLocation
	* 
	* @param array $emailing
	* @return void
	*/
	
	public function saveEnergyLocation ($energyLocation=array())
	{
		return $this->fill($energyLocation)->save();
	}
	
	/*
	* Update EnergyLocation
	* 
	* @param array $energyLocation
	* @return void
	*/
	
	public function updateEnergyLocation($energyLocation=array())
	{
		return $this->update($energyLocation);
    }
}
