<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termination extends Model
{
     /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['name'];
	
	/*
	* Save Termination
	* 
	* @param array $termination
	* @return void
	*/
	
	public function saveTermination($termination=array())
	{
		return $this->fill($termination)->save();
	}
	
	/*
	* Update Termination
	* 
	* @param array $termination
	* @return void
	*/
	
	public function updateTermination($termination=array())
	{
		return $this->update($termination);
	}	
}
