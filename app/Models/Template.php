<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['title','module','text','text_json'];
    
     /*
	* Save Template
	* 
	* @param array $template
	* @return void
    */
    
    public function saveTemplate ($template=array())
	{
		return $this->fill($template)->save();
	}
	
	/*
	* Update Template
	* 
	* @param array $template
	* @return void
	*/
	
	public function updateTemplate($template=array())
	{
		return $this->update($template);
	}
}
