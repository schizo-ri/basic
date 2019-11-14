<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Document extends Model
{

	protected $fillable = ['employee_id','path','title','description'];
	
	/*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
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
