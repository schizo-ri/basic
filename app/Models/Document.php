<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Document extends Model implements HasMedia
{
	use HasMediaTrait;
	
	protected $fillable = ['employee_id','path','title'];
	
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

	public function getCoverAttribute() {
		return $this->getMedia( collectionName['cover'])->last();
	}

	public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion(name['thumb'] )
              ->width(150)
              ->height(100);
    }
}
