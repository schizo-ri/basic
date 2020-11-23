<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesigningComment extends Model
{
    protected $fillable = [
        'designing_id','user_id','comment'];


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
        * Save DesigningComment
        * 
        * @param array $designingComment
        * @return void
	*/
	public function saveDesigningComment($designingComment=array())
	{
		return $this->fill($designingComment)->save();
	}
	
	/*
        * Update DesigningComment
        * 
        * @param array $designingComment
        * @return void
	*/
	
	public function updateDesigningComment($designingComment=array())
	{
		return $this->update($designingComment);
    }
}
