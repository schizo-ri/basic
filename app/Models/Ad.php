<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','category_id','subject','description', 'price'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}
	
	/*
	* The Eloquent category model name
	* 
	* @var string
	*/
	protected static $categoryModel = 'App\Models\AdCategory'; 
	
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
	* Save Ad
	* 
	* @param array $ad
	* @return void
	*/
	public function saveAd($ad=array())
	{
		return $this->fill($ad)->save();
	}
	
	/*
	* Update Ad
	* 
	* @param array $ad
	* @return void
	*/
	
	public function updateAd($ad=array())
	{
		return $this->update($ad);
	}	
}
