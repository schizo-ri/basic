<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkDiaryItem extends Model
{
    /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
       'diary_id','task_id','time','description'];

    /*
	* The Eloquent WorkDiary model name
	* 
	* @var string
	*/
    protected static $workDiaryModel = 'App\Models\WorkDiary'; 
 
    /*
	* The Eloquent WorkTask model name
	* 
	* @var string
	*/
	protected static $workTaskModel = 'App\Models\WorkTask';
    
    
    /*
	* Returns the WorkTask relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function workTask()
	{
		return $this->belongsTo(static::$workTaskModel,'task_id');
    }

    /*
	* Returns the WorkDiary relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function workDiary()
	{
		return $this->belongsTo(static::$workDiaryModel,'diary_id');
    }

    /*
	* Save WorkDiaryItem
	* 
	* @param array $workDiaryItem
	* @return void
	*/
	public function saveWorkDiaryItem($workDiaryItem=array())
	{
		return $this->fill($workDiaryItem)->save();
	}
	
	/*
	* Update WorkDiaryItem
	* 
	* @param array $workDiaryItem
	* @return void
	*/
	
	public function updateWorkDiaryItem($workDiaryItem=array())
	{
		return $this->update($workDiaryItem);
    }	   
}
