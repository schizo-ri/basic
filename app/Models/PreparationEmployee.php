<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparationEmployee extends Model
{
   
    protected $fillable = [
        'preparation_id','user_id','start','end'];

    /*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $userModel = 'App\User'; 

    /*
	* The Eloquent preparation model name
	* 
	* @var string
	*/
	protected static $preparationModel = 'App\Models\Preparation'; 
    
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
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function preparation()
	{
		return $this->belongsTo(static::$preparationModel,'preparation_id');
    }

   /*
	* Save PreparationEmployee
	* 
	* @param array $preparationEmployee
	* @return void
	*/
	public function savePreparationEmployee($preparationEmployee=array())
	{
		return $this->fill($preparationEmployee)->save();
	}
	
	/*
	* Update PreparationEmployee
	* 
	* @param array $preparationEmployee
	* @return void
	*/
	
	public function updatePreparationEmployee($preparationEmployee=array())
	{
		return $this->update($preparationEmployee);
    }      
}
