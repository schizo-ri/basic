<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryEmployee extends Model
{
    protected $fillable = [
        'mark','description'];
        
    /*
	* Save CategoryEmployee
	* 
	* @param array $category
	* @return void
	*/
	public function saveCategory($category=array())
	{
		return $this->fill($category)->save();
	}
	
	/*
	* Update CategoryEmployee
	* 
	* @param array $category
	* @return void
	*/
	
	public function updateCategory($category=array())
	{
		return $this->update($category);
	}	
}
