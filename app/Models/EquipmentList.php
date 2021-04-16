<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentList extends Model
{
    protected $fillable = [
        'preparation_id','product_number','mark', 'name', 'unit', 'quantity', 'quantity2','comment','delivered','replace_item','replaced_item_id','user_id','level1','stavka_id_level1', 'stavka_id_level2'];
    
    /*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $preparationModel = 'App\Models\Preparation'; 

	/*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $updatesModel = 'App\Models\ListUpdate'; 
	
	/*
	* Returns the listUpdate relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasmany
	*/
	
	public function updates()
	{
		return $this->hasMany(static::$updatesModel,'item_id');
	}

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
