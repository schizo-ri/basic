<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesigningEmployee extends Model
{
    protected $fillable = [
        'user_id','designing_id','start_date'];

    /*
        * The Eloquent project model name
        * 
        * @var string
	*/
    protected static $userModel = 'App\User'; 

    /*
        * The Eloquent project model name
        * 
        * @var string
	*/
    protected static $designingModel = 'App\Models\Designing'; 

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
	public function designing()
	{
		return $this->belongsTo(static::$designingModel,'designing_id');
    }

    /*
	* Save DesigningEmployee
	* 
	* @param array $designingEmployee
	* @return void
	*/
	public function saveDesigningEmployee($designingEmployee=array())
	{
		return $this->fill($designingEmployee)->save();
	}
	
	/*
	* Update DesigningEmployee
	* 
	* @param array $designingEmployee
	* @return void
	*/
	
	public function updateDesigningEmployee($designingEmployee=array())
	{
		return $this->update($designingEmployee);
	}
}
