<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
	
	/* The attributes thet are mass assignable
	*
	* @var array
	*/
    protected $fillable = ['type','ERP_leave_type','erp_task_id','employee_id','start_date','end_date','start_time','end_time','comment','approve','approve_reason','approved_id','approved_date','decree','ERP_leave_type'];
	
	/*
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $employeesModel = 'App\Models\Employee'; 	
	
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
	* The Eloquent employee model name
	* 
	* @var string
	*/
	protected static $absenceTypeModel = 'App\Models\AbsenceType'; 
	
	/*
	* Returns the authorized relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function approved()
	{
		return $this->belongsTo(static::$employeesModel,'approved_id');
	}
	
	/*
	* Save Absence
	* 
	* @param array $absence
	* @return void
	*/
	
	/*
	* Returns the authorized relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function absence()
	{
		return $this->belongsTo(static::$absenceTypeModel,'type');
	}
	
	public function saveAbsence($absence=array())
	{
		return $this->fill($absence)->save();
	}
	
	/*
	* Update Absence
	* 
	* @param array $absence
	* @return void
	*/
	
	public function updateAbsence($absence=array())
	{
		return $this->update($absence);
	}	

	/*
		* Absence IZL
		* 
		* @param $user_id, $type
		* @return - zahtjevi Izlasci za djelatnika
	*/
	public static function AllAbsenceUser($user_id, $type)
	{
		return Absence::where('employee_id',$user_id)->where('type', AbsenceType::where('mark',$type)->first()->id )->get();
	}

	/*
		* Absence - za traženi mjesec i traženi tip
		* 
		* @param $type, mjesec
		* @return - zahtjevi Izlasci za djelatnika
	*/
	public static function AbsencesForMonth( $month, $year )
	{
		$absences = Absence::whereMonth('start_date', $month)->whereYear('start_date', $year)->get();
		$absences = $absences->merge( Absence::whereMonth('end_date', $month)->whereYear('end_date',$year)->get());
		
		return $absences;
	}

	/*
		* Absence IZL - za traženi mjesec
		* 
		* @param $user_id, $type, mjesec
		* @return - zahtjevi Izlasci za djelatnika
	*/
	public static function AllAbsenceUserMY($user_id, $type, $month, $year)
	{
		return Absence::where('employee_id',$user_id)->where('type', AbsenceType::where('mark',$type)->first()->id )->whereMonth('start_date',month)->whereYear('start_date',$year)->get();
	}

	public static function getYears () 
	{
		$absences = Absence::get();

		$years = $absences->unique(function($absence){
          return date('Y', strtotime($absence['start_date']) );
         })->map(function($absence){
          return date('Y', strtotime($absence['start_date']) ); 
		 })->sort()->toArray();
		 
		 return $years;
	}

	public static function getYearsMonth () 
	{
		$absences = Absence::get();

		$month = $absences->unique(function($absence){
          return date('Y-m', strtotime($absence['start_date']) );
         })->map(function($absence){
          return date('Y-m', strtotime($absence['start_date']) ); 
		 })->sort()->toArray();
		 
		 return $month;
	}
	
}
