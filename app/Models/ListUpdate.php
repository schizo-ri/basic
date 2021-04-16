<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Users\IlluminateUserRepository;

class ListUpdate extends Model
{
    
    protected $fillable = [
        'item_id','quantity','user_id'];
    
    /*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $equipmentListModel = 'App\Models\EquipmentList'; 

	 /*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $userModel = 'App\User'; 

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
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function user()
	{
		return $this->belongsTo(static::$userModel,'user_id');
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
