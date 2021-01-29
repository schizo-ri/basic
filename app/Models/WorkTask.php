<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkTask extends Model
{
     /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	*/
	protected $fillable = [
        'name','description'];

	/*
	 * The Eloquent WorkDiaryItem model name
	 * 
	 * @var string
	*/
    protected static $workDiaryItemModel = 'App\Models\WorkDiaryItem'; 

    /*
	* Returns the WorkDiaryItem relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasMany
    */

	public function hasWorkDiary()
	{
		return $this->hasMany(static::$workDiaryItemModel,'task_id');
    }

    /*
	 * Save WorkTask
	 * 
	 * @param array $workTask
	 * @return void
	*/
	public function saveWorkTask($workTask=array())
	{
		return $this->fill($workTask)->save();
	}
	
	/*
	 * Update WorkTask
	 * 
	 * @param array $workTask
	 * @return void
	*/
	
	public function updateWorkTask($workTask=array())
	{
		return $this->update($workTask);
    }	   
}
