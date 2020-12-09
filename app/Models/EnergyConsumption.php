<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyConsumption extends Model
{
    protected $fillable = ['energy_id','location_id','date','counter','comment'];
    
    /*
	* The Eloquent EnergySource model name
	* 
	* @var string
	*/
	protected static $sourceModel = 'App\Models\EnergySource'; 
   
	 /*
	* The Eloquent EnergyLocation model name
	* 
	* @var string
	*/
	protected static $locationModel = 'App\Models\EnergyLocation'; 
	
	 /*
	* Returns the EnergySource relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/

	public function source()
	{
		return $this->belongsTo(static::$sourceModel,'energy_id');
    }
	
	/*
	* Returns the EnergyLocation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function location()
	{
		return $this->belongsTo(static::$locationModel,'location_id');
    }
	
    /*
	* Save EnergyConsumption
	* 
	* @param array $energyConsumption
	* @return void
	*/
	
	public function saveEnergyConsumption ($energyConsumption=array())
	{
		return $this->fill($energyConsumption)->save();
	}
	
	/*
	* Update EnergyConsumption
	* 
	* @param array $energyConsumption
	* @return void
	*/
	
	public function updateEnergyConsumption($energyConsumption=array())
	{
		return $this->update($energyConsumption);
	}	

	public function lastCounter ( $energy_id, $location_id )
	{
		$energyConsumption = EnergyConsumption::where('energy_id',$energy_id)->where('location_id',$location_id)->orderBy('date','DESC')->first();
		$counter = null;
		if($energyConsumption) {
			$counter = $energyConsumption->counter;
		}
	
		return  $counter;
	}

	public function lastCounter_Skip ( $energy_id, $location_id )
	{
		$energyConsumption = EnergyConsumption::where('energy_id',$energy_id)->where('location_id',$location_id)->orderBy('date','DESC')->skip(1)->first();
		$counter = null;
		if($energyConsumption) {
			$counter = $energyConsumption->counter;
		}
	
		return  $counter;
	}

	public function prevConsumption ( $id )
	{
		$energyConsumption = EnergyConsumption::find($id);
		
		$location_id = $energyConsumption->location_id;
		$energy_id = $energyConsumption->energy_id;
		
		$date =  $energyConsumption->date;
		$prevConsumption = EnergyConsumption::where('energy_id', $energy_id)->where('location_id',$location_id)->where('date','<',$date )->orderBy('date','DESC')->first();
		
		return  $prevConsumption;
	}
}