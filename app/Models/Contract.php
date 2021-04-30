<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['customer_id','template_id','contract_no','date','duration','invoice_no','invoice_date','package_prints_bw','package_prints_c','debenture_amount'];

    /*
	* The Eloquent Customer model name
	* 
	* @var string
	*/
	protected static $customerModel = 'App\Models\Customer'; 

	 /*
	* The Eloquent ContractTemplate model name
	* 
	* @var string
	*/
	protected static $contractTemplateModel = 'App\Models\ContractTemplate'; 
    
    /*
	* The Eloquent ContractSubject model name
	* 
	* @var string
	*/
	protected static $contractSubjectModel = 'App\Models\ContractSubject'; 

	/*
	* Returns the Customer relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\belongsTo
	*/
	
	public function template()
	{
		return $this->belongsTo(static::$contractTemplateModel,'template_id');
	}

	/*
	* Returns the Customer relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\belongsTo
	*/
	
	public function customer()
	{
		return $this->belongsTo(static::$customerModel,'customer_id');
	}
    
    /*
	* Returns the ContractSubject relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasSubjects()
	{
		return $this->hasMany(static::$contractSubjectModel,'contract_id');
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
