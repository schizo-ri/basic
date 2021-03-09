<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sentinel;

class Post extends Model
{	
	//use Sluggable;
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','to_employee_id','to_department_id','title','content','status'];
	
	/*
	* The Eloquent employee model names
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee';
	
	/*
	* The Eloquent comments model name
	* 
	* @var string
	*/
	protected static $commentsModel = 'App\Models\Comment'; 	
	
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
	* Returns the comments relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function comments()
	{
		return $this->hasMany(static::$commentsModel,'post_id')->orderBy('created_at','ASC');
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
	
	public static function PostToEmployee($employee)
	{
		$employee_work = $employee->work;
		$posts = null;

		if( $employee_work ) {
			$employee_department = $employee_work->department;
			
			if($employee_department->level1 == 0 ) {
				$posts = Post::where('employee_id', $employee->id)
					->orWhere('to_employee_id', $employee->id)
					->orWhere('to_department_id', $employee_department->id)
					->orderBy('updated_at','DESC')->with('comments')->get();
			} else {
				$department_l1 = Department::find($employee_department->level2);
				if($department_l1->level1 == 0 ) {
					$posts = Post::where('employee_id', $employee->id)
					->orWhere('to_employee_id', $employee->id)
					->orWhere('to_department_id', $employee_department->id)
					->orWhere('to_department_id', $department_l1->id)
					->orderBy('updated_at','DESC')->with('comments')->get();
				} else {
					$department_l0 = Department::find($department_l1->level2);
					$posts = Post::where('employee_id', $employee->id)
						->orWhere('to_employee_id', $employee->id)
						->orWhere('to_department_id', $employee_department->id)
						->orWhere('to_department_id', $department_l1->id)
						->orWhere('to_department_id', $department_l0->id)
						->orderBy('updated_at','DESC')->with('comments')->get();
				}
			}
		}
			
		return $posts;
	}	
	
	public static function countComment_all()
	{
		$comment_count = 0;
		
		if( Sentinel::getUser() ) {
			$employee = Sentinel::getUser()->employee;
			if( $employee ){
				if($employee->work) {
					$posts = Post::where('employee_id', $employee->id)->orWhere('to_employee_id', $employee->id)->orWhere('to_department_id',$employee->work->department->id)->get();
				} else {
					$posts = Post::where('employee_id', $employee->id)->orWhere('to_employee_id', $employee->id)->get();
	
				}
				foreach($posts as $post) {
					$count = $post->comments->where('to_employee_id', $employee->id)->where('status',0)->count();
					$comment_count += $count;
				}
			} 
	
		}
		return $comment_count;
	}
}
