<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $fillable = ['name'];

    /*
	* The Eloquent Document model name
	* 
	* @var string
	*/
	protected static $documentModel = 'App\Models\Document'; 
    
	/*
	* Returns the project relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasDocuments()
	{
		return $this->hasMany(static::$documentModel,'document_id');
	}	

    /*
	* Save DocumentCategory
	* 
	* @param array $documentCategory
	* @return void
	*/
	public function saveDocumentCategory($documentCategory=array())
	{
		return $this->fill($documentCategory)->save();
	}
	
	/*
	* Update DocumentCategory
	* 
	* @param array $documentCategory
	* @return void
	*/
	
	public function updateDocumentCategory($documentCategory=array())
	{
		return $this->update($documentCategory);
	}	
}
