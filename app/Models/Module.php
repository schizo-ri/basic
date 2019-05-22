<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name','description'
	];
	
	/*
	* Save Module
	* 
	* @param array $module
	* @return void
	*/
	public function saveModule($module=array())
	{
		return $this->fill($module)->save();
	}
	
	/*
	* Update Module
	* 
	* @param array $module
	* @return void
	*/
	
	public function updateModule($module=array())
	{
		return $this->update($module);
	}	
}
