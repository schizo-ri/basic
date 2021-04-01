<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Document extends Model
{

	protected $fillable = ['employee_id','path','title','description','category_id','active'];
	
	/*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* The Eloquent DocumentCategory model name
	* 
	* @var string
	*/
	protected static $documentCategoryModel = 'App\Models\DocumentCategory'; 

	/*
	* Returns the Users relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}

	/*
	* Returns the Users relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function category()
	{
		return $this->belongsTo(static::$documentCategoryModel,'category_id');
	}
	
	/*
	* Save Document
	* 
	* @param array $document
	* @return void
	*/
	
	public function saveDocument ($document=array())
	{
		return $this->fill($document)->save();
	}
	
	/*
	* Update Document
	* 
	* @param array $document
	* @return void
	*/
	
	public function updateDocument($document=array())
	{
		return $this->update($document);
	}	

}
