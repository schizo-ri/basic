<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name','address','city','oib','active'];

	/*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $projectModel = 'App\Models\Project'; 
	
	
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
