<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = [
		'name'];

    /*
	* Save Manufacturer
	* 
	* @param array $manufacturer
	* @return void
	*/
	public function saveManufacturer($manufacturer=array())
	{
		return $this->fill($manufacturer)->save();
	}
	
	/*
	* Update Manufacturer
	* 
	* @param array $manufacturer
	* @return void
	*/
	
	public function updateManufacturer($manufacturer=array())
	{
		return $this->update($manufacturer);
    }
}
