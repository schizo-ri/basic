<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorization extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','construction_site_id','competence_id'];

    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employee_Model = 'App\Models\Employee'; 

    /*
	* The Eloquent ConstructionSite model name
	* 
	* @var string
	*/
	protected static $constructionSiteModel = 'App\Models\ConstructionSite'; 
    
    /*
	* The Eloquent Competence model name
	* 
	* @var string
	*/
	protected static $competenceModel = 'App\Models\Competence'; 
    
    /*
	* Returns the Users relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employee_Model,'employee_id');
	}

    /*
	* Returns the Users relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function constructionSite()
	{
		return $this->belongsTo(static::$constructionSiteModel,'construction_site_id');
	}

    /*
	* Returns the Users relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function competence()
	{
		return $this->belongsTo(static::$competenceModel,'competence_id');
	}

    /*
	* Save Categorization
	* 
	* @param array $categorization
	* @return void
	*/
	public function saveCategorization($categorization=array())
	{
		return $this->fill($categorization)->save();
	}
	
	/*
	* Update Categorization
	* 
	* @param array $categorization
	* @return void
	*/
	
	public function updateCategorization($categorization=array())
	{
		return $this->update($categorization);
    }
}
