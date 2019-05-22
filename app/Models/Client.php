<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'name','address','city','oib','first_name','last_name','email','phone'
	];
	
	/*
	* Save Client
	* 
	* @param array $client
	* @return void
	*/
	public function saveClient($client=array())
	{
		return $this->fill($client)->save();
	}
	
	/*
	* Update Client
	* 
	* @param array $client
	* @return void
	*/
	
	public function updateClient($client=array())
	{
		return $this->update($client);
	}	
}
