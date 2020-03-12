<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListReport extends Model
{
    protected $fillable = [
        'item_id','delivered_before','delivered_after'];

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
	* Save ListReport
	* 
	* @param array $listReport
	* @return void
	*/
	public function saveListReport($listReport=array())
	{
		return $this->fill($listReport)->save();
	}
	
	/*
	* Update ListReport
	* 
	* @param array $listReport
	* @return void
	*/
	
	public function updateListReport($listReport=array())
	{
		return $this->update($listReport);
	}	
    
}
