<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgglomerationStation extends Model
{
    protected $fillable = [
        'agglomeration_id','name','location','comment'];

    /*
     * The Eloquent Agglomeration model name
     * 
     * @var string
	*/
    protected static $agglomerationModel = 'App\Models\Agglomeration'; 

    /*
     * The Eloquent AgglomerationStationList model name
     * 
     * @var string
	*/
    protected static $AgglomerationStationListModel = 'App\Models\AgglomerationStationList'; 
    
    /*
	* Returns the Agglomeration relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\belongsTo
	*/
	public function agglomeration()
	{
		return $this->belongsTo(static::$agglomerationModel,'agglomeration_id');
    }

	/*
	 * Returns the AgglomerationStationList relationship
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function hasList()
	{
		return $this->hasMany(static::$AgglomerationStationListModel,'station_id');
    }

    /*
	* Save AgglomerationStation
	* 
	* @param array $agglomerationStation
	* @return void
	*/
	public function saveAgglomerationStation($agglomerationStation=array())
	{
		return $this->fill($agglomerationStation)->save();
	}
	
	/*
	* Update AgglomerationStation
	* 
	* @param array $agglomerationStation
	* @return void
	*/
	
	public function updateAgglomerationStation($agglomerationStation=array())
	{
		return $this->update($agglomerationStation);
    }
}
