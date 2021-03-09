<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstructionSite extends Model
{
      /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['name'];

    /*
	* Save ConstructionSite
	* 
	* @param array $constructionSite
	* @return void
	*/
	public function saveConstructionSite($constructionSite=array())
	{
		return $this->fill($constructionSite)->save();
	}
	
	/*
	* Update ConstructionSite
	* 
	* @param array $constructionSite
	* @return void
	*/
	
	public function updateConstructionSite($constructionSite=array())
	{
		return $this->update($constructionSite);
    }
}
