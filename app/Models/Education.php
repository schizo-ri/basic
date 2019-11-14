<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
	/**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['name','status','to_department_id'];
	
	/*
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\Department'; 
	
	/*
	* Returns the projekt relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function department()
	{
		return $this->belongsTo(static::$departmentModel,'to_department_id');
	}	
	
	/*
	* Save Education
	* 
	* @param array $education
	* @return void
	*/
	public function saveEducation($education=array())
	{
		return $this->fill($education)->save();
	}
	
	/*
	* Update Education
	* 
	* @param array $education
	* @return void
	*/
	
	public function updateEducation($education=array())
	{
		return $this->update($education);
	}	
}
