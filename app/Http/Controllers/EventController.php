<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Employee;
use App\Models\Absence;
use Sentinel;
use App\Http\Requests\EventRequest;
use DateTime;
use DateInterval;
use DatePeriod;

class EventController extends Controller
{
     /**
   * Set middleware to quard controller.
   *
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
    public function index()
    {
        $empl = Sentinel::getUser()->employee;
        if($empl) {
            $events = Event::where('employee_id', $empl->id)->get();
        } else {
            $events = array();
        }
        
    	$permission_dep = array();
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		} 
        
        $dataArr = array();
        
		foreach($events as $event1) {
			array_push($dataArr, ['name' => "event", 'date' => $event1->date]);
        }
        
        $absences = Absence::where('approve',1)->get();
        $today = date('Y-m-d');
        $select_day = explode('-',$today);  //get from URL
        $dan_select = $select_day[2];
        $mj_select = $select_day[1];
        $god_select = $select_day[0];

        foreach($absences as $absence) {
            $begin = new DateTime($absence->start_date);
            $end = new DateTime($absence->end_date);
            $end->setTime(0,0,1);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            foreach ($period as $dan) {
                if(date_format($dan,'Y') == $god_select) {  // ako je trenutna godina
                    array_push($dataArr, ['name' => $absence->absence['mark'], 'date' => date_format($dan,'Y-m-d'), 'employee' => $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name']]);
                }
            }
        }

        $employees = Employee::get();

        foreach($employees as $employee) {
            array_push($dataArr, ['name' => "birthday", 'date' => $employee->b_day, 'employee' => $employee->user['first_name'] . ' ' . $employee->user['last_name'] ]);
        }
        
        $start = new DateTime('00:00');
        $times = 24;

        for ($i = 0; $i < $times-1; $i++) {
            $hours_array[] = $start->add(new DateInterval('PT1H'))->format('H:i');
        }

		return view('Centaur::events.index',['dataArr'=>$dataArr,'events'=>$events,'employees'=>$employees, 'absences'=>$absences, 'permission_dep' => $permission_dep,'hours_array' => $hours_array]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  	=> $employee->id,
			'title'  		=> $request['title'],
			'date'  		=> $request['date'],
			'time1' 		=> $request['time1'],
			'time2' 		=> $request['time2'],
			'description'   => $request['description']
		);
		
		$event = new Event();
		$event->saveEvent($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('events.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function countDays ($dataArr, $dan) {

        $dani_event=0; 
        $dani_rodjendani = 0; 
        $dani_odmor = 0;
        
        foreach($dataArr as $arr) {
            if( $arr['date'] == $dan) {
                if($arr['name'] == 'event') {
                    $dani_event++;
                } 
                if($arr['name'] !='event' && $arr['name'] !='birthday') {
                    $dani_odmor++;
                }
            }
            if( date("m-d",strtotime($arr['date'])) == date("m-d",strtotime($dan))) {
                if ($arr['name'] == 'birthday') {
                    $dani_rodjendani++;
                }
            }
        }

        return ['dani_event' => $dani_event, 'dani_odmor' => $dani_odmor, 'dani_rodjendani' => $dani_rodjendani];
    }

    public static function selectedDay ($dan) {
        $select_day = '';
        $dan_select = '';
        $mj_select = '';
        $mj_select = '';
        $god_select = '';

        $select_day = explode('-',$dan);
       
        $week_day = date("D", strtotime($dan) );
        $month =  date("F",  strtotime($dan) );
        $dan_select = $select_day[2];
        $mj_select = $select_day[1];
        $god_select = $select_day[0];

        return ['week_day' => $week_day, 'month' => $month, 'dan_select' => $dan_select, 'mj_select' => $mj_select, 'god_select' => $god_select];
    }
}
