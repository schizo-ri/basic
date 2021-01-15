<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailStyle extends Model
{
    protected $fillable = ['mail_id','style_header','style_body','style_footer','style_header_input','style_body_input','style_footer_input'];

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
	* Save MailStyle
	* 
	* @param array $mailStyle
	* @return void
	*/
	
	public function saveMailStyle($mailStyle=array())
	{
		return $this->fill($mailStyle)->save();
	}
	
	/*
	* Update MailStyle
	* 
	* @param array $mailStyle
	* @return void
	*/
	
	public function updateMailStyle($mailStyle=array())
	{
		return $this->update($mailStyle);
	}
}
