<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkRecord extends Model
{
    /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
        'employee_id','start','end','status'];
        
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
    
    /*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
    }
    
    /*
	* Save WorkRecords
	* 
	* @param array $workRecords
	* @return void
	*/
	public function saveWorkRecords($workRecords=array())
	{
		return $this->fill($workRecords)->save();
	}
	
	/*
	* Update WorkRecords
	* 
	* @param array $workRecords
	* @return void
	*/
	
	public function updateWorkRecords($workRecords=array())
	{
		return $this->update($workRecords);
	}	
}
