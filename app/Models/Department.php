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
	* The Eloquent Department model name
	* 
	* @var string
	*/
	protected static $DepartmentModel = 'App\Models\Department'; 

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
	
	public function roofLevel()
	{
		return $this->belongsTo(static::$DepartmentModel,'level2');
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

	public static function allDepartmentsEmployeesEmail ( $department_id ) 
	{
		$employees = Employee::employees_firstNameASC();
		$employeesEmail = array();

		$department = Department::where('id', $department_id)->first();
		if($department->level1 == 0) {
			if( $department->name == 'Svi') {
				foreach ($employees as $employee) {
					array_push($employeesEmail, $employee->email );
				}
			} else {
				foreach ($employees as $employee) {
					if(  $employee->hasEmployeeDepartmen && count( $employee->hasEmployeeDepartmen) > 0) {
						if(  $employee->hasEmployeeDepartmen->where('department_id',$department->id)->first()) {
							array_push($employeesEmail, $employee->email );
						}
					}
				}
			}
		}
		if($department->level1 == 1) {
			foreach ($employees as $employee) {
				if(  $employee->hasEmployeeDepartmen && count( $employee->hasEmployeeDepartmen) > 0) {
					if(  $employee->hasEmployeeDepartmen->where('department_id',$department->id)->first()) {
						array_push($employeesEmail, $employee->email );
					}
				}
			/* 	if ( $employee && $employee->work && $employee->work->department_id == $department->id) {
					array_push($employeesEmail, $employee->email );
				} */
			}
			$departments2 = Department::where('level2', $department->id)->get();
			foreach ($departments2 as $department2) {
				foreach ($employees as $employee) {
					if(  $employee->hasEmployeeDepartmen && count( $employee->hasEmployeeDepartmen) > 0) {
						if(  $employee->hasEmployeeDepartmen->where('department_id',$department2->id )->first()) {
							array_push($employeesEmail, $employee->email );
						}
					}
				/* 	if ( $employee && $employee->work && $employee->work->department_id == $department2->id) {
						array_push($employeesEmail, $employee->email );
					} */
				}
			}
		}
		if($department->level1 == 2) {
			foreach ($employees as $employee) {
				if(  $employee->hasEmployeeDepartmen && count( $employee->hasEmployeeDepartmen) > 0) {
					if(  $employee->hasEmployeeDepartmen->where('department_id', $department_id )->first()) {
						array_push($employeesEmail, $employee->email );
					}
				}
			}
		}
		return array_unique($employeesEmail);
	}
}
