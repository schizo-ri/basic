<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name','address','city','oib','email','phone','director'
	];
	
	/*
	* Save Company
	* 
	* @param array $company
	* @return void
	*/
	public function saveCompany($company=array())
	{
		return $this->fill($company)->save();
	}
	
	/*
	* Update Company
	* 
	* @param array $company
	* @return void
	*/
	
	public function updateCompany($company=array())
	{
		return $this->update($company);
	}	
}
