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
		'company_id','name','level1','level2','email','employee_id'
	];
	
	/*
	* The Eloquent company model name
	* 
	* @var string
	*/
	protected static $companyModel = 'App\Models\Company'; 

	/*
	* The Eloquent EmployeeDepartment model name
	* 
	* @var string
	*/
	protected static $employeeDepartmentModel = 'App\Models\EmployeeDepartment'; 

	/*
	* The Eloquent work model name
	* 
	* @var string
	*/
	protected static $workModel = 'App\Models\Work';

	/*
	* Returns the employeeDepartment relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function hasEmployeeDepartment()
	{
		return $this->hasMany(static::$employeeDepartmentModel,'department_id');
	}

	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee';
	
	/*
	* Returns the company relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}
	
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
	* Returns the company relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function hasWorks()
	{
		return $this->hasMany(static::$workModel,'department_id');
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
