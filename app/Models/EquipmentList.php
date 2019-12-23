<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentList extends Model
{
    
    protected $fillable = [
        'preparation_id','product_number','name', 'unit', 'quantity', 'delivered'];
    
    /*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $preparationModel = 'App\Models\Preparation'; 

	/*
	* Returns the preparation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function preparation1()
	{
		return $this->belongsTo(static::$preparationModel,'preparation_id');
    }
    
    /*
	* Save EquipmentList
	* 
	* @param array $equipmentList
	* @return void
	*/
	public function saveEquipmentList($equipmentList=array())
	{
		return $this->fill($equipmentList)->save();
	}
	
	/*
	* Update EquipmentList
	* 
	* @param array $equipmentList
	* @return void
	*/
	
	public function updateEquipmentList($equipmentList=array())
	{
		return $this->update($equipmentList);
	}	

}
