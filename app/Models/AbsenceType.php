<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenceType extends Model
{
	
	/* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['name','mark','min_days','max_days'];
	
	/*
	* Save AbsenceType
	* 
	* @param array $absenceType
	* @return void
	*/
	
	public function saveAbsenceType($absenceType=array())
	{
		return $this->fill($absenceType)->save();
	}
	
	/*
	* Update AbsenceType
	* 
	* @param array $absenceType
	* @return void
	*/
	
	public function updateAbsenceType($absenceType=array())
	{
		return $this->update($absenceType);
	}	
}
