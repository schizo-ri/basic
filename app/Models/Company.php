<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name','address','city','oib','email','phone','director'
	];
	
	/*
	* The Eloquent Department model name
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\Department'; 

	/*
	* Returns the Department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	
	public function hasDepartments()
	{
		return $this->hasMany(static::$departmentModel,'company_id');
	}
	
	/*
	* Returns the Department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	
	public function hasDepartments_level0()
	{
		return $this->hasDepartments()->where('level1', 0);
	}

	/*
	* Returns the Department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	
	public function hasDepartments_level1()
	{
		return $this->hasDepartments()->where('level1',1);
	}

	/*
	* Returns the Department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
	*/
	
	public function hasDepartments_level2()
	{
		return $this->hasDepartments()->where('level1', 2);
	}


	/*
	* Save Company
	* 
	* @param array $company
	* @return void
	*/
	public function saveCompany($company=array())
	{
		return $this->fill($company)->save();
	}
	
	/*
	* Update Company
	* 
	* @param array $company
	* @return void
	*/
	
	public function updateCompany($company=array())
	{
		return $this->update($company);
	}	
}
