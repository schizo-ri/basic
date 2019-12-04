<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name','last_name','category_id'];
	
	/*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $categoryModel = 'App\Models\CategoryEmployee'; 

	/*
	* Returns the category relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function category()
	{
		return $this->belongsTo(static::$categoryModel,'category_id');
	}
	
    /*
	* Save Employee
	* 
	* @param array $employee
	* @return void
	*/
	public function saveEmployee($employee=array())
	{
		return $this->fill($employee)->save();
	}
	
	/*
	* Update Employee
	* 
	* @param array $employee
	* @return void
	*/
	
	public function updateEmployee($employee=array())
	{
		return $this->update($employee);
	}	
}
