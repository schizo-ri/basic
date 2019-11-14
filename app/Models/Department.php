<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Department extends Model
{

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'company_id','name','level1','level2','email'
	];
	
	/*
	* The Eloquent company model name
	* 
	* @var string
	*/
	protected static $companyModel = 'App\Models\Company'; 
	
	/*
	* Returns the company relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function company()
	{
		return $this->belongsTo(static::$companyModel,'company_id');
	}
	
	/*
	* The Eloquent departmentRole model name
	* 
	* @var string
	*/
	protected static $departmentRoleModel = 'App\Models\DepartmentRole'; 
	
	/**
     * Get the comments for the blog post.
     */
    public function departmentRole()
    {
        return $this->hasMany(static::$departmentRoleModel);
    }
	
	/*
	* Save Department
	* 
	* @param array $department
	* @return void
	*/
	public function saveDepartment($department=array())
	{
		return $this->fill($department)->save();
	}
	
	/*
	* Update Department
	* 
	* @param array $department
	* @return void
	*/
	
	public function updateDepartment($department=array())
	{
		return $this->update($department);
	}	
}
