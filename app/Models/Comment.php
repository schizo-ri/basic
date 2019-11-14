<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
   
	/**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','post_id','content','status'];
	
	/*
	* The Eloquent employee model names
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee';
	
	/*
	* The Eloquent posts model name
	* 
	* @var string
	*/
	protected static $postsModel = 'App\Models\Post'; 	
	
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
	* Returns the post  relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function post()
	{
		return $this->belongsTo(static::$postsModel,'post_id');
	}	
	
	/*
	* Save Comment
	* 
	* @param array $comment
	* @return void
	*/
	
	public function saveComment($comment=array())
	{
		return $this->fill($comment)->save();
	}
	
	/*
	* Update Comment
	* 
	* @param array $comment
	* @return void
	*/
	
	public function updateComment($comment=array())
	{
		return $this->update($comment);
	}	
}
