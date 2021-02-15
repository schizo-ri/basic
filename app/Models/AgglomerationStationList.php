<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgglomerationStationList extends Model
{
    protected $fillable = [
        'station_id','reference','group','description','price','quantity'];

    /*
     * The Eloquent AgglomerationStation model name
     * 
     * @var string
	*/
    protected static $agglomerationStationModel = 'App\Models\AgglomerationStation'; 
    
    /*
	* Returns the Agglomeration relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\belongsTo
	*/
	public function station()
	{
		return $this->belongsTo(static::$agglomerationStationModel,'station_id');
    }

    /*
	* Save AgglomerationStationList
	* 
	* @param array $agglomerationStationList
	* @return void
	*/
	public function saveAgglomerationStationList($agglomerationStationList=array())
	{
		return $this->fill($agglomerationStationList)->save();
	}
	
	/*
	* Update AgglomerationStationList
	* 
	* @param array $agglomerationStationList
	* @return void
	*/
	
	public function updateAgglomerationStationList($agglomerationStationList=array())
	{
		return $this->update($agglomerationStationList);
    }
}