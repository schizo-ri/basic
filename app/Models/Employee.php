<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name','last_name'];
        
    /*
	* Save Employee
	* 
	* @param array $employee
	* @return void
	*/
	public function saveEmployee($employee=array())
	{
		return $this->fill($employee)->save();
	}
	
	/*
	* Update Employee
	* 
	* @param array $employee
	* @return void
	*/
	
	public function updateEmployee($employee=array())
	{
		return $this->update($employee);
	}	
}
