<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name','address','city','oib','active','representedBy'];

	/*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $projectModel = 'App\Models\Project'; 

	/*
	* The Eloquent CustomerLocation model name
	* 
	* @var string
	*/
	protected static $customerLocationModel = 'App\Models\CustomerLocation'; 
		
	/*
	* Returns the project relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function project()
	{
		return $this->hasMany(static::$projectModel,'customer_id')->orderBy('created_at','DESC');
	}	

	/*
	* Returns the CustomerLocation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasLocations()
	{
		return $this->hasMany(static::$customerLocationModel,'customer_id');
	}	
	
	/*
	* Save Customer
	* 
	* @param array $Customer
	* @return void
	*/
	
	public function saveCustomer($customer=array())
	{
		return $this->fill($customer)->save();
	}
	
	/*
	* Update Customer
	* 
	* @param array $Customer
	* @return void
	*/
	
	public function updateCustomer($customer=array())
	{
		return $this->update($customer);
	}	
}
