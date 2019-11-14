<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentRole extends Model
{

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'department_id','permissions'
	];
	
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
	
	public function department()
	{
		return $this->belongsTo(static::$departmentModel,'department_id');
	}

	/*
	* Save DepartmentRole
	* 
	* @param array $departmentRole
	* @return void
	*/
	public function saveDepartmentRole($departmentRole=array())
	{
		return $this->fill($departmentRole)->save();
	}
	
	/*
	* Update DepartmentRole
	* 
	* @param array $departmentRole
	* @return void
	*/
	
	public function updateDepartmentRole($departmentRole=array())
	{
		return $this->update($departmentRole);
	}	
}
