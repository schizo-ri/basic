<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLocation extends Model
{
    protected $fillable = ['customer_id','address','city'];
    
	/*
	* The Eloquent Customer model name
	* 
	* @var string
	*/
	protected static $customerModel = 'App\Models\Customer'; 
    
    /*
	* Returns the customer relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function customer()
	{
		return $this->belongsTo(static::$customerModel,'customer_id');
	}

    /*
	* Save CustomerLocation
	* 
	* @param array $customerLocation
	* @return void
	*/
	
	public function saveCustomerLocation($customerLocation=array())
	{
		return $this->fill($customerLocation)->save();
	}
	
	/*
	* Update CustomerLocation
	* 
	* @param array $customerLocation
	* @return void
	*/
	
	public function updateCustomerLocation($customerLocation=array())
	{
		return $this->update($customerLocation);
	}	
}
