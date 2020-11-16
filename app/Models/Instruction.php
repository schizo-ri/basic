<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $fillable = ['department_id','title','description','active'];
    
    /*
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\Department'; 
	
	/*
	* Returns the department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
		
	/*
	* The Eloquent comments model name
	* 
	* @var string
	*/
	protected static $commentsInstructionModel = 'App\Models\CommentInstruction'; 	

	/*
	* Returns the comments relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function comments()
	{
		return $this->hasMany(static::$commentsInstructionModel,'instruction_id')->orderBy('created_at','DESC')->paginate(5);
	}	

	/*
	* Returns the department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function department()
	{
		return $this->belongsTo(static::$departmentModel,'department_id');
	}

	/*
	* Save Instruction
	* 
	* @param array $instruction
	* @return void
	*/
	
	public function saveInstruction ($instruction=array())
	{
		return $this->fill($instruction)->save();
	}
	
	/*
	* Update Instruction
	* 
	* @param array $instruction
	* @return void
	*/
	
	public function updateInstruction($instruction=array())
	{
		return $this->update($instruction);
	}	
}
