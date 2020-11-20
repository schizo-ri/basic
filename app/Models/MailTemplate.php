<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    protected $fillable = ['name','for_mail','description'];
    
	/*
	* Save MailTemplate
	* 
	* @param array $mailTemplate
	* @return void
	*/
	
	public function saveMailTemplate($mailTemplate=array())
	{
		return $this->fill($mailTemplate)->save();
	}
	
	/*
	* Update MailTemplate
	* 
	* @param array $mailTemplate
	* @return void
	*/
	
	public function updateMailTemplate($mailTemplate=array())
	{
		return $this->update($mailTemplate);
	}	
}
