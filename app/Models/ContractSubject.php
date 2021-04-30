<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractSubject extends Model
{
    protected $fillable = ['contract_id','location_id','name','serial_no','counter_bw','counter_c','flat_rate','price_a4_bw','price_a4_c','no_prints_bw','no_prints_c'];

    /*
	 * The Eloquent Contract model name
	 * 
	 * @var string
	*/
	protected static $contractModel = 'App\Models\Contract'; 

    /*
	 * The Eloquent CustomerLocation model name
	 * 
	 * @var string
	*/
	protected static $locationModel = 'App\Models\CustomerLocation'; 
    
    /*
	* Returns the contract relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function contract()
	{
		return $this->belongsTo(static::$contractModel,'contract_id');
	}

    /*
	* Returns the CustomerLocation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function location()
	{
		return $this->belongsTo(static::$locationModel,'location_id');
	}

    /*
	* Save ContractSubject
	* 
	* @param array $contractSubject
	* @return void
	*/
	
	public function saveContractSubject($contractSubject=array())
	{
		return $this->fill($contractSubject)->save();
	}
	
	/*
	* Update ContractSubject
	* 
	* @param array $contractSubject
	* @return void
	*/
	
	public function updateContractSubject($contractSubject=array())
	{
		return $this->update($contractSubject);
	}	

}
