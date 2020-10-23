<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
	protected $fillable = ['employee_id','to_department','to_employee','title','notice','text_json','schedule_date'];
	
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 
	
	/*
	* Returns the employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}

    /*
	* Save Notice
	* 
	* @param array $notice
	* @return void
	*/
	
	public function saveNotice ($notice=array())
	{
		return $this->fill($notice)->save();
	}
	
	/*
	* Update Notice
	* 
	* @param array $notice
	* @return void
	*/
	
	public function updateNotice($notice=array())
	{
		return $this->update($notice);
	}

	public static function getNotice ($sort) 
	{
		$today = date('Y-m-d'); // 2019-10-16
        $time = date('H:i:s'); // 14:49:05

		$notices1 = Notice::whereDate('schedule_date','<=', $today )->whereTime('schedule_date','<',  $time )->orderBy('schedule_date',$sort)->get();
        $notices2 = Notice::whereDate('schedule_date','<', $today )->orderBy('schedule_date',$sort)->get();
        $notices3 = Notice::where('schedule_date', null)->orderBy('schedule_date',$sort)->get();
		$notices = $notices1->merge( $notices2, $notices3 );

		return $notices;
	}
}
