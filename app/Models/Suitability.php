<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suitability extends Model
{
    protected $fillable = ['title','description','contact','email','phone','status'];

    /*
	* Save Benefit
	* 
	* @param array $benefit
	* @return void
	*/
	
	public function saveSuitability ($benefit=array())
	{
		return $this->fill($benefit)->save();
	}
	
	/*
	* Update Benefit
	* 
	* @param array $benefit
	* @return void
	*/
	
	public function updateSuitability($benefit=array())
	{
		return $this->update($benefit);
	}
}
