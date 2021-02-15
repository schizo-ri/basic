<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'number','supplier','comment'];

	/*
     * The Eloquent Agglomeration model name
     * 
     * @var string
	*/
	protected static $agglomerationModel = 'App\Models\Agglomeration'; 
	
	/*
		* The Eloquent ContractList model name
		* 
		* @var string
	*/
    protected static $contractListModel = 'App\Models\ContractList'; 
    
	/*
	* Returns the ContractList relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function hasList()
	{
		return $this->hasMany(static::$contractListModel,'contract_id');
	}
	
	/*
	* Returns the ContractList relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function hasAgglomeration()
	{
		return $this->hasMany(static::$agglomerationModel,'contract_id');
    }
    
    /*
	* Save Contract
	* 
	* @param array $contract
	* @return void
	*/
	public function saveContract($contract=array())
	{
		return $this->fill($contract)->save();
	}
	
	/*
	* Update Contract
	* 
	* @param array $contract
	* @return void
	*/
	
	public function updateContract($contract=array())
	{
		return $this->update($contract);
	}
}
