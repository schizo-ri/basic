<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRequest extends Model
{
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'client_id','modules','db','url'
	];
	
	/*
	* The Eloquent client model names
	* 
	* @var string
	*/
	protected static $clientModel = 'App\Models\Client';
	
	/*
	* Returns the client relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function client()
	{
		return $this->belongsTo(static::$clientModel,'client_id');
	}
	
	/*
	* Save ClientRequest
	* 
	* @param array $clientRequest
	* @return void
	*/
	public function saveClientRequest($clientRequest=array())
	{
		return $this->fill($clientRequest)->save();
	}
	
	/*
	* Update ClientRequest
	* 
	* @param array $clientRequest
	* @return void
	*/
	
	public function updateClientRequest($clientRequest=array())
	{
		return $this->update($clientRequest);
	}	
}
