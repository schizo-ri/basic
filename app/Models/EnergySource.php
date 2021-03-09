<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergySource extends Model
{
    protected $fillable = ['name','comment','no_counter'];
   
    /*
	* Save EnergySource
	* 
	* @param array $energySource
	* @return void
	*/
	
	public function saveEnergySource ($energySource=array())
	{
		return $this->fill($energySource)->save();
	}
	
	/*
	* Update EnergySource
	* 
	* @param array $energySource
	* @return void
	*/
	
	public function updateEnergySource($energySource=array())
	{
		return $this->update($energySource);
	}	
}
