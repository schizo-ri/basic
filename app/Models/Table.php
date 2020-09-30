<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{	
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name','emailing','description','shortcut_i','shortcut_c'
	];
	
	/*
	* Save Table
	* 
	* @param array $table
	* @return void
	*/
	public function saveTable($table=array())
	{
		return $this->fill($table)->save();
	}
	
	/*
	* Update Table
	* 
	* @param array $table
	* @return void
	*/
	
	public function updateTable($table=array())
	{
		return $this->update($table);
	}	
}
