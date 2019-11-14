<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeStatistic extends Model
{
	protected $fillable = ['employee_id','notice_id','status'];

     /*
	* The Eloquent Employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* Returns the Employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}

     /*
	* The Eloquent Notice model name
	* 
	* @var string
	*/
    protected static $noticeModel = 'App\Models\Notice'; 
    
    /*
	* Returns the Notice relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function notice()
	{
		return $this->belongsTo(static::$noticeModel,'notice_id');
    }
    
    /*
	* Save NoticeStatistic
	* 
	* @param array $notice_statistic
	* @return void
	*/
	
	public function saveStatistic ($notice_statistic=array())
	{
		return $this->fill($notice_statistic)->save();
	}
	
	/*
	* Update NoticeStatistic
	* 
	* @param array $notice_statistic
	* @return void
	*/
	
	public function updateStatistic($notice_statistic=array())
	{
		return $this->update($notice_statistic);
	}	
}
