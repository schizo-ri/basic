<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignSequence extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['campaign_id','text','start_date','send_interval'];
	
	/*
	* The Eloquent campaign model name
	* 
	* @var string
	*/
	protected static $campaignModel = 'App\Models\Campaign'; 
	
	/*
	* Returns the campaign relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function campaign()
	{
		return $this->belongsTo(static::$campaignModel,'campaign_id');
	}

	/*
	* Save CampaignSequence
	* 
	* @param array $campaignSequence
	* @return void
	*/
	public function saveCampaignSequence($campaignSequence=array())
	{
		return $this->fill($campaignSequence)->save();
	}
	
	/*
	* Update CampaignSequence
	* 
	* @param array $campaignSequence
	* @return void
	*/
	
	public function updateCampaignSequence($campaignSequence=array())
	{
		return $this->update($campaignSequence);
    }
}
