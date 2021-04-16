<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designing extends Model
{
    protected $fillable = [
        'project_no','name','cabinet_name','date','manager_id','designer_id','comment','start','end','active','finished'];

     /*
	* The Eloquent project model name
	* 
	* @var string
	*/
    protected static $userModel = 'App\User'; 
	
	/*
		* The Eloquent DesigningComment model name
		* 
		* @var string
	*/
	protected static $commentModel = 'App\Models\DesigningComment'; 
	
	/*
		* The Eloquent DesigningEmployee model name
		* 
		* @var string
	*/
	protected static $designingEmployeeModel = 'App\Models\DesigningEmployee'; 

    /*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function manager()
	{
		return $this->belongsTo(static::$userModel,'manager_id');
    }
    
    /*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function designer()
	{
		return $this->belongsTo(static::$userModel,'designer_id');
	}
	
	/*
	* Returns the DesigningComment relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function hasComments()
	{
		return $this->hasMany(static::$commentModel,'designing_id');
	}

	/*
	* Returns the DesigningComment relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function hasEmployees()
	{
		return $this->hasMany(static::$designingEmployeeModel,'designing_id');
	}
	
    /*
	* Save Designing
	* 
	* @param array $designing
	* @return void
	*/
	public function saveDesigning($designing=array())
	{
		return $this->fill($designing)->save();
	}
	
	/*
	* Update Designing
	* 
	* @param array $designing
	* @return void
	*/
	
	public function updateDesigning($designing=array())
	{
		return $this->update($designing);
	}
}
