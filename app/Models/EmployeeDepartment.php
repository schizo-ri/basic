<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sentinel;

class EmployeeDepartment extends Model
{
    protected $fillable = ['department_id','employee_id'];

    /*
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\Department'; 
	
	
	/*
	* Returns the projekt relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function department()
	{
		return $this->belongsTo(static::$departmentModel,'department_id');
	}	
	
	/*
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}	
	
	/*
	* Save EmployeeDepartment
	* 
	* @param array $employeeDepartment
	* @return void
	*/
	
	public function saveEmployeeDepartment($employeeDepartment=array())
	{
		return $this->fill($employeeDepartment)->save();
	}
	
	/*
	* Update EmployeeDepartment
	* 
	* @param array $department
	* @return void
	*/
	
	public function updateEmployeeDepartment($employeeDepartment=array())
	{
		return $this->update($employeeDepartment);
	}	

	public static function hasEmployeeDepartment_sort()
	{
		return EmployeeDepartment::join('employees','employees.id','employee_departments.employee_id')
								 ->join('users','users.id','employees.user_id')
								 ->join('departments','departments.id','employee_departments.department_id')
								 ->select('employee_departments.*','employees.user_id','users.first_name','users.last_name','departments.name')
								 ->where('employees.checkout',null)
								 ->orderBy('departments.name','ASC')
								 ->orderBy('users.last_name','ASC')->get();
	}

	/** Za voditelja odjelja - svi djelatnici radnih mjesta kojima je prvi nadređeni */
	public static function DepartmentEmployees ( $department_id )
	{
		$works = Work::where('first_superior', Sentinel::getUser()->employee->id )->get()->pluck('id')->toArray();
		$employees = Employee::whereIn('work_id', $works)->where('checkout', null)->get()->pluck('id')->toArray();
		return EmployeeDepartment::whereIn('employee_id', $employees)->get();
	}
}
