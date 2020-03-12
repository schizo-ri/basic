<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name','value','description'
	];
	
	/*
	* Save Setting
	* 
	* @param array $setting
	* @return void
	*/
	public function saveSetting($setting=array())
	{
		return $this->fill($setting)->save();
	}
	
	/*
	* Update Setting
	* 
	* @param array $setting
	* @return void
	*/
	
	public function updateSetting($setting=array())
	{
		return $this->update($setting);
	}	
}
