<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shortcut extends Model
{
    /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'employee_id','title','color','url'
    ];
    
    /*
	* The Eloquent employee model names
	* 
	* @var string
	*/
    protected static $employeeModel = 'App\Models\Employee';
    
    /*
	* Returns the users relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
    }
    
    /*
	* Save Shortcut
	* 
	* @param array $shortcut
	* @return void
	*/
	public function saveShortcut($shortcut=array())
	{
		return $this->fill($shortcut)->save();
	}
	
	/*
	* Update Shortcut
	* 
	* @param array $shortcut
	* @return void
	*/
	
	public function updateShortcut($shortcut=array())
	{
		return $this->update($shortcut);
	}	
	
}
