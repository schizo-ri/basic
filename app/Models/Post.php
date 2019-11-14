<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{	
	//use Sluggable;
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','to_employee_id','to_department_id','title','slug','content','status'];
	
	/*
	* The Eloquent employee model names
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee';
	
	/*
	* The Eloquent deparment model names
	* 
	* @var string
	*/
	protected static $deparmentModel = 'App\Models\Department';
	
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
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function to_employee()
	{
		return $this->belongsTo(static::$employeeModel,'to_employee_id');
	}
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function to_department()
	{
		return $this->belongsTo(static::$deparmentModel,'to_department_id');
	}
	
	/*
	* The Eloquent comments model name
	* 
	* @var string
	*/
	protected static $commentsModel = 'App\Models\Comment'; 	
	
	/*
	* Returns the comments relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function comments()
	{
		return $this->hasMany(static::$commentsModel,'post_id')->orderBy('created_at','DESC')->paginate(10);
	}	
	
	/*
	* Save Post
	* 
	* @param array $post
	* @return void
	*/
	
	public function savePost($post=array())
	{
		return $this->fill($post)->save();
	}
	
	/*
	* Update Post
	* 
	* @param array $post
	* @return void
	*/
	
	public function updatePost($post=array())
	{
		return $this->update($post);
	}	
	
}
