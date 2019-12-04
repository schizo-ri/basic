<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublishProject extends Model
{
    protected $fillable = [
        'name','project_id','project_no','duration','day_hours','saturday','start_date','end_date','categories'];

    /*
	* Save PublishProject
	* 
	* @param array $publishProject
	* @return void
	*/
	public function savePublishProject($publishProject=array())
	{
		return $this->fill($publishProject)->save();
	}
	
	/*
	* Update PublishProject
	* 
	* @param array $publishProject
	* @return void
	*/
	
	public function updatePublishProject($publishProject=array())
	{
		return $this->update($publishProject);
	}	
}
