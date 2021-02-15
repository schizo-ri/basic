<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agglomeration extends Model
{
    protected $fillable = [
		'contract_id','name','manager','designer','comment'];

    /*
     * The Eloquent AgglomerationStation model name
     * 
     * @var string
	*/
	protected static $agglomerationStationModel = 'App\Models\AgglomerationStation'; 
	
    /*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $userModel = 'Cartalyst\Sentinel\Users\EloquentUser'; 

	/*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $contractModel = 'App\Models\Contract'; 

	/*
	 * Returns the AgglomerationStation relationship
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function hasStation()
	{
		return $this->hasMany(static::$agglomerationStationModel,'agglomeration_id');
    }

	/*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function contract()
	{
		return $this->belongsTo(static::$contractModel,'contract_id');
	}

	/*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function managerUser()
	{
		return $this->belongsTo(static::$userModel,'manager');
	}

	/*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function designerUser()
	{
		return $this->belongsTo(static::$userModel,'designer');
	}

    /*
	* Save Agglomeration
	* 
	* @param array $agglomeration
	* @return void
	*/
	public function saveAgglomeration($agglomeration=array())
	{
		return $this->fill($agglomeration)->save();
	}
	
	/*
	* Update Agglomeration
	* 
	* @param array $agglomeration
	* @return void
	*/
	
	public function updateAgglomeration($agglomeration=array())
	{
		return $this->update($agglomeration);
    }
}
