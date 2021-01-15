<?php

namespace App\Models;
use Log;

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
	* The Eloquent MailText model name
	* 
	* @var string
	*/
	protected static $mailTextModel = 'App\Models\MailText'; 

	/*
	* Returns the Travel relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasOne
	*/
	
	public function mailText()
	{
		return $this->hasOne(static::$mailTextModel,'mail_id');
	}

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

	public static function textHeader ($mail_template) 
	{
		$mail_text = $mail_template->mailText;
		$template_text_header = array();
		Log::info( "mail_text: " );
		Log::info( $mail_text );
		if( $mail_text ) {
			$convert_to_array = explode(';', $mail_text->text_header);
			for($i=0; $i <= count($convert_to_array ); $i++){
				if(isset( $convert_to_array [$i]) &&  $convert_to_array [$i] != '' ) {
					$key_value = explode(':', $convert_to_array [$i]);
					if( isset($key_value [1]) ) {
						$template_text_header[$i] = $key_value[1];
					}
				}
			}
		}
		Log::info( $template_text_header );
		return $template_text_header;
	}

	public static function textBody ($mail_template) 
	{
		$mail_text = $mail_template->mailText;
		$template_text_body= array();
		if( $mail_text ) {
			$convert_to_array = explode(';', $mail_text->text_body);
			for($i=0; $i <= count($convert_to_array ); $i++){
				if(isset( $convert_to_array [$i])  &&  $convert_to_array [$i] != ''  ) {
					$key_value = explode(':', $convert_to_array [$i]);
					if( isset($key_value [1]) ) {
						$template_text_body[$i] = $key_value[1];
					}
				}
			}
		}
		
		return $template_text_body;
	}

	public static function textFooter ($mail_template) 
	{
		$mail_text = $mail_template->mailText;
		$template_text_footer = array();

		if( $mail_text ) {
			$convert_to_array = explode(';', $mail_text->text_footer);
			for($i=0; $i <= count($convert_to_array ); $i++){
				if(isset( $convert_to_array [$i]) ) {
					$key_value = explode(':', $convert_to_array [$i]);
					if( isset($key_value [1]) ) {
						$template_text_footer[$i] = $key_value[1];
					}
				}
			}
		}
		
		return $template_text_footer;
	}
}
