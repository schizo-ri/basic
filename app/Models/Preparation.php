<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preparation extends Model
{
    protected $fillable = [
        'project_no','name','preparation','mechanical_processing'];

    /*
	* Save Preparation
	* 
	* @param array $preparation
	* @return void
	*/
	public function savePreparation($preparation=array())
	{
		return $this->fill($preparation)->save();
	}
	
	/*
	* Update Preparation
	* 
	* @param array $preparation
	* @return void
	*/
	
	public function updatePreparation($preparation=array())
	{
		return $this->update($preparation);
    }
}
