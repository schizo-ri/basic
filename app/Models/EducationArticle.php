<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationArticle extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['article','subject','theme_id','employee_id','status'];
	
	/*
	* The Eloquent educationTheme model name
	* 
	* @var string
	*/
	protected static $educationThemeModel = 'App\Models\EducationTheme'; 
	
	/*
	* Returns the educationTheme relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function educationTheme()
	{
		return $this->belongsTo(static::$educationThemeModel,'theme_id');
	}
	
	
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
	* Save EducationArticle
	* 
	* @param array $educationArticle
	* @return void
	*/
	public function saveEducationArticle($educationArticle=array())
	{
		return $this->fill($educationArticle)->save();
	}
	
	/*
	* Update EducationArticle
	* 
	* @param array $educationArticle
	* @return void
	*/
	
	public function updateEducationArticle($educationArticle=array())
	{
		return $this->update($educationArticle);
	}	
}
