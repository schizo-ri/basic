<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BasicAbsenceController;
use App\Models\WorkRecord;
use App\Models\Absence;
use App\Models\Employee;
use App\Models\TravelOrder;
use App\Models\Locco;
use Sentinel;
use DB;
use DateTime;
use DateInterval;
use DatePeriod;


class WorkRecordController extends Controller
{
     /**
	*
	* Set middleware to quard controller.
	* @return void
	*/
	public function __construct()
	{
		$this->middleware('sentinel.auth');
	}
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($_GET['date']) ) {
            $mjesec = date('m',strtotime( $_GET['date'] . '-1'));
            $godina = date('Y',strtotime( $_GET['date']));
            $prev_month = new DateTime($_GET['date'] . '-1');
            $prev_month->modify('-1 month');
            $month_before = date_format($prev_month,'m');
            $year_before = date_format($prev_month,'Y');
            $next_month = new DateTime($_GET['date'] . '-1');
            $next_month->modify('+1 month');
            $month_after = date_format($next_month,'m');
            $year_after = date_format($next_month,'Y');
        } else {
            $mjesec = date('m');
            $godina = date('Y');
           
            $prev_month = new DateTime(date('Y-m-d'));
            $prev_month->modify('-1 month');
            $month_before = date_format($prev_month,'m');
            $year_before = date_format($prev_month,'Y');
            $next_month = new DateTime(date('Y-m-d'));
            $next_month->modify('+1 month');
            $month_after = date_format($next_month,'m');
            $year_after = date_format($next_month,'Y');
        }
   
        $work_records = WorkRecord::whereMonth('start', $mjesec )->whereYear('start', $godina )->get();
        $months = $this->months_workingHours();

        foreach($work_records as $record){
            $time1 = date_create($record->start);
            if( $record->end ) {
                $time2 = date_create($record->end);
                $interval = date_diff($time1,$time2);
                $record->interval = date('H:i',strtotime( $interval->h .':'.$interval->i));
            } else {
                $record->interval = null;
            }
        }
        // zahtjevi za izostanak
        $absences = Absence::whereMonth('start_date', $mjesec )->whereYear('start_date', $godina )->where('approve','<>',null)->get();
        $absences = $absences->merge(Absence::whereMonth('start_date', $month_before )->whereYear('start_date', $year_before )->where('approve','<>',null)->get());
        $absences = $absences->merge(Absence::whereMonth('start_date', $month_after )->whereYear('start_date', $year_after )->where('approve','<>',null)->get());
      
        $holidays = BasicAbsenceController::holidays();
       
        foreach ($absences as $absence) {
            $absence->days = array();
            $begin = new DateTime($absence['start_date']);
            $end = new DateTime($absence['end_date']);
            $end->setTime(0,0,1);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $i = 0;
            foreach ($period as $dan) {
                if(! in_array(date_format($dan,'Y-m-d'), $holidays) && date_format($dan,'N') < 6) {
                    $absence->days += [$i => date_format($dan,'Y-m-d')];
                    $i++;
                }
            }
        }

        $empl = Sentinel::getUser()->employee;
        $permission_dep = array();
        if($empl) {
            $permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        }
       
        for($d=1; $d<=31; $d++){
			$time=mktime(12, 0, 0, $mjesec, $d, $godina);  
			if (date('m', $time)==$mjesec){   
					$list[]=date('Y-m-d D', $time);
			}
        }
        $employees = Employee::where('employees.id','<>',1)->get();
        return view('Centaur::work_records.index', ['work_records' => $work_records, 'permission_dep' => $permission_dep, 'list' => $list,'employees' => $employees,'months' => $months,'absences' => $absences]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $employees = Employee::where('employees.id','<>',1)->get();

       return view('Centaur::work_records.create', ['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(isset($request['employee_id'])) {
            $employee_id = $request['employee_id'];
        } else {
            $employee = Sentinel::getUser()->employee;
            if($employee) {
                $employee_id = $employee->id;
            }
        }
        
        if($request['entry'] == 'entry') { 
            if(isset($request['start'])) {
                $start = $request['start'];
            } else {
                $start = date('Y-m-d H:i');
            }
            $data = array(
                'employee_id'  	 => $employee_id,
                'start'  		=>  $start,
            );
            	
            $workRecord = new WorkRecord();
            $workRecord->saveWorkRecords($data);
            
            session()->flash('success',  __('ctrl.data_save'));

        } else if( $request['checkout'] == 'checkout' ) {
            if(isset($request['end'])) {
                $end = $request['end'];
            } else {
                $end = date('Y-m-d H:i');
            }
            $workRecord = WorkRecord::where('employee_id', $employee->id)->whereDate('start', date('Y-m-d'))->first();
            if($workRecord) {
                $data = array(
                    'employee_id'  	=> $employee_id,
                    'end'  			=> $end,
                );
                $workRecord->updateWorkRecords($data);
            } else {
                session()->flash('error',  __('ctrl.data_error'));
            }
        }
	
        return redirect()->back();	
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $employee = Employee::find($id);

        if(isset($_GET['date']) ) {
            $mjesec = date('m',strtotime( $_GET['date'] . '-1'));
            $godina = date('Y',strtotime( $_GET['date']));
            $prev_month = new DateTime($_GET['date'] . '-1');
            $prev_month->modify('-1 month');
            $month_before = date_format($prev_month,'m');
            $year_before = date_format($prev_month,'Y');
            $next_month = new DateTime($_GET['date'] . '-1');
            $next_month->modify('+1 month');
            $month_after = date_format($next_month,'m');
            $year_after = date_format($next_month,'Y');
        } else {
            $mjesec = date('m');
            $godina = date('Y');
           
            $prev_month = new DateTime(date('Y-m-d'));
            $prev_month->modify('-1 month');
            $month_before = date_format($prev_month,'m');
            $year_before = date_format($prev_month,'Y');
            $next_month = new DateTime(date('Y-m-d'));
            $next_month->modify('+1 month');
            $month_after = date_format($next_month,'m');
            $year_after = date_format($next_month,'Y');
        }
         
        $work_records = WorkRecord::where('employee_id', $employee->id)->whereMonth('start', $mjesec )->whereYear('start', $godina )->get();

        foreach($work_records as $record){
            $time1 = date_create($record->start);
            $time2 = date_create($record->end);
            $interval = date_diff($time1,$time2);
            
            $record->interval = date('H:i',strtotime( $interval->h .':'.$interval->i));
        }
        // zahtjevi za izostanak
        $absences = Absence::where('employee_id', $employee->id)->whereMonth('start_date', $mjesec )->whereYear('start_date', $godina )->where('approve','<>',null)->get();
        $absences = $absences->merge(Absence::where('employee_id', $employee->id)->whereMonth('start_date', $month_before )->whereYear('start_date', $year_before )->where('approve','<>',null)->get());
        $absences = $absences->merge(Absence::where('employee_id', $employee->id)->whereMonth('start_date', $month_after )->whereYear('start_date', $year_after )->where('approve','<>',null)->get());
     
        $holidays = BasicAbsenceController::holidays();
        $holidaysThisYear = BasicAbsenceController::holidaysThisYear($godina);
        
        foreach ($absences as $absence) {
            $absence->days = array();
            $absence->mark = $absence->absence['mark'];
            $begin = new DateTime($absence['start_date']);
            $end = new DateTime($absence['end_date']);
            $end->setTime(0,0,1);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $i = 0;
            foreach ($period as $dan) {
                if(! in_array(date_format($dan,'Y-m-d'), $holidays) && date_format($dan,'N') < 6) {
                    $absence->days += [$i => date_format($dan,'Y-m-d')];
                    $i++;
                }
            }
        }

        $travelOrders = TravelOrder::where('employee_id', $employee->id)->whereMonth('start_date', $mjesec )->whereYear('start_date', $godina )->get();
        $travelOrders = $travelOrders->merge(TravelOrder::where('employee_id', $employee->id)->whereMonth('end_date', $mjesec )->whereYear('end_date', $godina )->get());

        foreach ($travelOrders as $travel) {
            $travel->travelDays = array();
           
            $begin = new DateTime($travel['start_date']);
            $end = new DateTime($travel['end_date']);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            $i = 0;
            foreach ($period as $dan) {
                if(! in_array(date_format($dan,'Y-m-d'), $holidays) && date_format($dan,'N') < 6) {
                    $travel->travelDays += [$i => date_format($dan,'Y-m-d')];
                    $i++;
                }
            }
        }

        $loccos = Locco::where('employee_id', $employee->id)->whereMonth('date', $mjesec )->whereYear('date', $godina )->get();
        
        foreach($loccos as $locco){
            $time1 = date_create($locco->date);
            if( $locco->end_date ) {
                $time2 = date_create($locco->end_date);
                $interval = date_diff($time1,$time2);
                $locco->interval = date('H:i',strtotime( $interval->h .':'.$interval->i));
            } else {
                $locco->interval = null;
            }
        }
      
        $empl = Sentinel::getUser()->employee;
        $permission_dep = array();
        if($empl) {
            $permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        }
        $sum = array();
        for($d=1; $d<=31; $d++){
			$time=mktime(12, 0, 0, $mjesec, $d, $godina);  
			if (date('m', $time)==$mjesec){   
                    $list[]=date('Y-m-d', $time);
                    $sum[date('Y-m-d', $time)] = 0;
                    
			}
        }
      
        return view('Centaur::work_records.show', ['employee' => $employee,'work_records' => $work_records, 'travelOrders' => $travelOrders,'loccos' => $loccos,'permission_dep' => $permission_dep, 'list' => $list, 'sum' => $sum,'absences' => $absences,'holidaysThisYear' => $holidaysThisYear,'month' => $godina.'-'.$mjesec .'-1']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $work_record = WorkRecord::find($id);
        $employees = Employee::where('employees.id','<>',1)->get();

        return view('Centaur::work_records.edit', ['work_record' => $work_record,'employees' => $employees]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $workRecord = WorkRecord::find($id);

        if(isset($request['employee_id'])) {
            $employee_id = $request['employee_id'];
        } else {
            $employee = Sentinel::getUser()->employee;
            if($employee) {
                $employee_id = $employee->id;
            }
        }
        if(isset($request['end'])) {
            $end = $request['end'];
        } else {
            $end = date('Y-m-d H:i');
        }

        $data = array(
            'employee_id'  	 => $employee_id,
            'start'  		=>  $request['start'],
            'end'  		    =>  $end,
        );
        $workRecord->updateWorkRecords($data);
        session()->flash('success',  __('ctrl.data_edit'));

        return redirect()->back();	
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $workRecord = WorkRecord::find($id);
        $workRecord->delete();
        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
    
    public function months_workingHours()
    {
        $months = array();
        $work_records = WorkRecord::get();
        foreach ($work_records as $key => $work_record) {
           array_push($months, date('Y-m',strtotime($work_record->start )));
        }
        $months = array_unique($months);
        return $months;
    }

}