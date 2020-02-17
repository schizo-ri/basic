<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignRecipient extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['campaign_id','department_id'];
	
	/*
	* The Eloquent department model name
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\Department'; 
	
	/*
	* Returns the department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function department()
	{
		return $this->belongsTo(static::$departmentModel,'department_id');
	}

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
		return $this->belongsTo(static::$campaignModel,'campagne_id');
    }
    
    /*
	* Save CampaignRecipient
	* 
	* @param array $campaignRecipient
	* @return void
	*/
	public function saveCampaignRecipient($campaignRecipient=array())
	{
		return $this->fill($campaignRecipient)->save();
	}
	
	/*
	* Update CampaignRecipient
	* 
	* @param array $campaignRecipient
	* @return void
	*/
	
	public function updateCampaignRecipient($campaignRecipient=array())
	{
		return $this->update($campaignRecipient);
    }
}