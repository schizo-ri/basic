<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractTemplate extends Model
{
    protected $fillable = ['name', 'general_conditions'];

    /*
	 * The Eloquent Contract model name
	 * 
	 * @var string
	*/
	protected static $contractModel = 'App\Models\Contract'; 
    
    /*
	 * The Eloquent ContractArticle model name
	 * 
	 * @var string
	*/
	protected static $articleModel = 'App\Models\ContractArticle'; 

    /*
	* Returns the Contract relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasContracts()
	{
		return $this->hasMany(static::$contractModel,'template_id');
	}

    /*
	* Returns the ContractArticle relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasArticles()
	{
		return $this->hasMany(static::$articleModel,'template_id');
	}	

    /*
	* Save ContractTemplate
	* 
	* @param array $contractTemplate
	* @return void
	*/
	
	public function saveContractTemplate($contractTemplate=array())
	{
		return $this->fill($contractTemplate)->save();
	}
	
	/*
	* Update ContractTemplate
	* 
	* @param array $contractTemplate
	* @return void
	*/
	
	public function updateContractTemplate($contractTemplate=array())
	{
		return $this->update($contractTemplate);
	}	
}
