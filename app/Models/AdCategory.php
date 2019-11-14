<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdCategory extends Model
{
	/**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['name'];
	
	/*
	* Save Category
	* 
	* @param array $category
	* @return void
	*/
	public function saveCategory($category=array())
	{
		return $this->fill($category)->save();
	}
	
	/*
	* Update Category
	* 
	* @param array $category
	* @return void
	*/
	
	public function updateCategory($category=array())
	{
		return $this->update($category);
	}	
}
