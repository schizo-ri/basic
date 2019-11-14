<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationTheme extends Model
{
	/**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['name','education_id'];
	
	/*
	* The Eloquent works model name
	* 
	* @var string
	*/
	protected static $educationModel = 'App\Models\Education'; 
	
	/*
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function education()
	{
		return $this->belongsTo(static::$educationModel,'education_id');
	}
	
	
	/*
	* Save EducationTheme
	* 
	* @param array $education
	* @return void
	*/
	public function saveEducationTheme($educationTheme=array())
	{
		return $this->fill($educationTheme)->save();
	}
	
	/*
	* Update EducationTheme
	* 
	* @param array $educationTheme
	* @return void
	*/
	
	public function updateEducationTheme($educationTheme=array())
	{
		return $this->update($educationTheme);
	}	//
}
