<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractArticle extends Model
{
    protected $fillable = ['template_id','article_text'];

    /*
	* The Eloquent ContractTemplate model name
	* 
	* @var string
	*/
	protected static $contractTemplateModel = 'App\Models\ContractTemplate'; 

    /*
	* Returns the template relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function template()
	{
		return $this->belongsTo(static::$contractTemplateModel,'template_id');
	}

    /*
	* Save ContractArticle
	* 
	* @param array $contractArticle
	* @return void
	*/
	
	public function saveContractArticle($contractArticle=array())
	{
		return $this->fill($contractArticle)->save();
	}
	
	/*
	* Update ContractArticle
	* 
	* @param array $contractArticle
	* @return void
	*/
	
	public function updateContractArticle($contractArticle=array())
	{
		return $this->update($contractArticle);
	}	

}
