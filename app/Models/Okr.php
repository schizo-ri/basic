<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class Okr extends Model
{
    /**
	* The attributes thet are mass assignable
	*
	* @var array
	*/
	protected $fillable = ['employee_id','name','comment','start_date','end_date','progress','status','comment_admin'];
	
    /*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee'; 	

	/*
	* The Eloquent keyResult model name
	* 
	* @var string
	*/
	protected static $keyResultModel = 'App\Models\KeyResult'; 	
	
	/*
	* The Eloquent Okr model name
	* 
	* @var string
	*/
	protected static $okrCommentModel = 'App\Models\OkrComment'; 	

	/*
	* Returns the employees relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function employee()
	{
		return $this->belongsTo(static::$employeesModel,'employee_id');
	}
	
	/*
	* Returns the okrComment relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasComments()
	{
		return $this->hasMany(static::$okrCommentModel,'okr_id');
    }	

	/*
	* Returns the keyResult relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	
	public function hasKeyResults()
	{
		return $this->hasMany(static::$keyResultModel,'okr_id');
    }	

	/*
	* Save Okr
	* 
	* @param array $okr
	* @return void
	*/
	public function saveOkr($okr=array())
	{
		return $this->fill($okr)->save();
	}
	
	/*
	* Update Okr
	* 
	* @param array $okr
	* @return void
	*/
	
	public function updateOkr($okr=array())
	{
		return $this->update($okr);
	}	

	public static function allEmployeeOnOKRs () 
	{
		$okrs = Okr::with('hasKeyResults')->get();

		$employees = $okrs->map(function ($okr, $key) {
			if($okr->employee ) {
				return $okr->employee;
			}
        });
	
		foreach ($okrs as $okr ) {
			$keyResults = $okr->hasKeyResults->where('employee_id','<>',null);
			if($keyResults && count($keyResults) > 0) {
				$employees = $employees->merge(
					$keyResults->map(function ($keyResult, $key) {
						if($keyResult && $keyResult->employee) {
							return $keyResult->employee;
						}
					})
				);
				foreach ($okr->hasKeyResults as $key => $keyResult) {
					$keyResultTasks = $keyResult->hasTasks->where('employee_id','<>',null);
					if($keyResultTasks && count($keyResultTasks) > 0) {
						$employees = $employees->merge(
							$keyResultTasks->map(function ($keyResultTask, $key) {
								if ($keyResultTask->employee);
								return $keyResultTask->employee;
							})
						);
					}
				}
			}
		}

		return $employees->unique();
	}
}
