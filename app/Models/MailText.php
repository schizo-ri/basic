<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailText extends Model
{
    protected $fillable = ['mail_id','text_header','text_body','text_footer'];
    /*
        * The Eloquent MailTemplate model name
        * 
        * @var string
	*/
	protected static $mailTemplateModel = 'App\Models\MailTemplate'; 	
	
	/*
        * Returns the employees relationship
        * 
        * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function mailTemplate()
	{
		return $this->belongsTo(static::$mailTemplateModel,'mail_id');
    }
    
    /*
	* Save MailText
	* 
	* @param array $mailText
	* @return void
	*/
	
	public function saveMailText($mailText=array())
	{
		return $this->fill($mailText)->save();
	}
	
	/*
	* Update MailText
	* 
	* @param array $mailText
	* @return void
	*/
	
	public function updateMailText($mailText=array())
	{
		return $this->update($mailText);
    }	
}
