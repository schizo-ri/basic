<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = ['name','description','comment','url','url2','status'];

    /*
	* Save Benefit
	* 
	* @param array $benefit
	* @return void
	*/
	
	public function saveBenefit ($benefit=array())
	{
		return $this->fill($benefit)->save();
	}
	
	/*
	* Update Benefit
	* 
	* @param array $benefit
	* @return void
	*/
	
	public function updateBenefit($benefit=array())
	{
		return $this->update($benefit);
	}
}
