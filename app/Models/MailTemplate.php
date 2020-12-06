<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    protected $fillable = ['name','for_mail','description'];
    
	/*
	* The Eloquent MailStyle model name
	* 
	* @var string
	*/
	protected static $mailStyleModel = 'App\Models\MailStyle'; 

	/*
	* Returns the Travel relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasOne
	*/
	
	public function mailStyle()
	{
		return $this->hasOne(static::$mailStyleModel,'mail_id');
	}

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
