<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['name','description','type','active','start_date','start_time'];
	

	/*
	* The Eloquent CampaignSequence model name
	* 
	* @var string
	*/
	protected static $sequenceseModel = 'App\Models\CampaignSequence'; 

	/**
     * Get the sequencese for the Campaign.
     */
    public function campaignSequence()
    {
        return $this->hasMany(static::$sequenceseModel);
    }
	
    /*
	* Save Campaign
	* 
	* @param array $campaign
	* @return void
	*/
	public function saveCampaign($campaign=array())
	{
		return $this->fill($campaign)->save();
	}
	
	/*
	* Update Campaign
	* 
	* @param array $campaign
	* @return void
	*/
	
	public function updateCampaign($campaign=array())
	{
		return $this->update($campaign);
    }
}
