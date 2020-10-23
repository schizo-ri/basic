<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryEmployee extends Model
{
     /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['user_id','work_id','superior_id','reg_date','checkout','comment','father_name','mather_name','oib','oi','oi_expiry','b_day','b_place','mobile','email','priv_mobile','priv_email','prebiv_adresa','prebiv_grad','title','qualifications','marital','size','shoe_size'];
    
    
    /*
	* The Eloquent user model name
	* 
	* @var string
	*/
	protected static $userModel = 'App\User'; 
    
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
    
	/*
	* The Eloquent works model name
	* 
	* @var string
	*/
	protected static $workModel = 'App\Models\Work'; 
    
    /*
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function user()
	{
		return $this->belongsTo(static::$userModel,'user_id');
    }
    
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'superior_id');
    }

    /*
	* Returns the works relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function work()
	{
		return $this->belongsTo(static::$workModel,'work_id');
    }
    
    /*
	* Save TemporaryEmployee
	* 
	* @param array $temporaryEmployee
	* @return void
	*/
	public function saveTemporaryEmployee($temporaryEmployee=array())
	{
		return $this->fill($temporaryEmployee)->save();
	}
	
	/*
	* Update TemporaryEmployee
	* 
	* @param array $temporaryEmployee
	* @return void
	*/
	
	public function updateTemporaryEmployee($temporaryEmployee=array())
	{
		return $this->update($temporaryEmployee);
	}	
}
