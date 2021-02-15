<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractList extends Model
{
    protected $fillable = [
        'contract_id','reference','group','description','price','quantity'];

    /*
		* The Eloquent Contract model name
		* 
		* @var string
	*/
    protected static $contractModel = 'App\Models\Contract'; 
    
	/*
	* Returns the Contract relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\belongsTo
	*/
	public function contract()
	{
		return $this->belongsTo(static::$contractModel,'contract_id');
    }

    /*
	* Save ContractList
	* 
	* @param array $contractList
	* @return void
	*/
	public function saveContractList($contractList=array())
	{
		return $this->fill($contractList)->save();
	}
	
	/*
	* Update ContractList
	* 
	* @param array $contractList
	* @return void
	*/
	
	public function updateContractList($contractList=array())
	{
		return $this->update($contractList);
	}
}
