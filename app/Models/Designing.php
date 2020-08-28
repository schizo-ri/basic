<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designing extends Model
{
    protected $fillable = [
        'project_no','name','date','manager_id','designer_id','comment'];

     /*
	* The Eloquent project model name
	* 
	* @var string
	*/
    protected static $userModel = 'App\User'; 
    
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
