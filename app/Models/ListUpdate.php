<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListUpdate extends Model
{
    
    protected $fillable = [
        'item_id','quantity'];
    
    /*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $equipmentListModel = 'App\Models\EquipmentList'; 

	/*
	* Returns the preparation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function equipmentList()
	{
		return $this->belongsTo(static::$equipmentListModel,'item_id');
    }
    
    /*
	* Save ListUpdate
	* 
	* @param array $listUpdate
	* @return void
	*/
	public function saveListUpdate($listUpdate=array())
	{
		return $this->fill($listUpdate)->save();
	}
	
	/*
	* Update ListUpdate
	* 
	* @param array $listUpdate
	* @return void
	*/
	
	public function updateListUpdate($listUpdate=array())
	{
		return $this->update($listUpdate);
	}	
}
