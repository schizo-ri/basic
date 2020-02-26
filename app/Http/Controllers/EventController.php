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
use Spatie\CalendarLinks\Link;

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
        $permission_dep = array();
        $dataArr = array();

        if($empl) {
            $events = Event::where('employee_id', $empl->id)->get();
            if( $empl->work && $empl->work->department && $empl->work->department->departmentRole ) {
                $permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
            }
            
            if(count($events) > 0) {
                foreach($events as $event1) {
                    array_push($dataArr, ['name' => "event", 'type' => __('calendar.event'), 'date' => $event1->date, 'title' => $event1->title]);
                }
            }
            
            $absences = Absence::where('approve',1)->get();
            $today = date('Y-m-d');
            $select_day = explode('-',$today);  //get from URL
            $dan_select = $select_day[2];
            $mj_select = $select_day[1];
            $god_select = $select_day[0];

            if (count($absences)>0) {
                foreach($absences as $absence) {
                    $begin = new DateTime($absence->start_date);
                    $end = new DateTime($absence->end_date);
                    $end->setTime(0,0,1);
                    $interval = DateInterval::createFromDateString('1 day');
                    $period = new DatePeriod($begin, $interval, $end);
                    foreach ($period as $dan) {
                        if(date_format($dan,'Y') == $god_select) {  // ako je trenutna godina
                            array_push($dataArr, ['name' => $absence->absence['mark'],'type' => $absence->absence['name'], 'date' => date_format($dan,'Y-m-d'), 'start_time' =>  $absence->start_time, 'end_time' =>  $absence->end_time, 'employee' => $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name']]);
                        }
                    }
                }
            }
          
            $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
            if(count($employees)>0) {
                foreach($employees as $employee) {
                    $dan = $god_select . '-' . date('m-d', strtotime($employee->b_day));
                    array_push($dataArr, ['name' => 'birthday','type' => __('basic.birthday'), 'date' => $dan, 'employee' => $employee->user['first_name'] . ' ' . $employee->user['last_name'] ]);
                }
            }
            
            $start = new DateTime('00:00');
            $times = 24;

            for ($i = 0; $i < $times-1; $i++) {
                $hours_array[] = $start->add(new DateInterval('PT1H'))->format('H:i');
            }

            return view('Centaur::events.index',['dataArr'=> $dataArr,'events'=>$events,'employees'=>$employees, 'absences'=>$absences, 'permission_dep' => $permission_dep,'hours_array' => $hours_array]);
        } else {
            $message = session()->flash('error', __('ctrl.path_not_allow'));
            return redirect()->back()->withFlashMessage($message);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $type = 'event';
        
        if ($request['time1']) {
            $time = $request['time1'];
            $time2 = strtotime( $time ) + 3600;
            $time2 = date('H:i',$time2 );
        } else {
            $time = '08:00';
            $time2 = '09:00';
        }
        if ($request['date']) {
            $date = $request['date'];
        } else {
            $date = date('Y-m-d');
        }
       
        if(isset($request['type'])) {
            if($request['type'] == 'task') {
                $type = 'task';
            }
        }

        return view('Centaur::events.create', ['type' => $type, 'time' => $time,'time2' => $time2, 'date' => $date ]);       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {

        $host = $_SERVER['REQUEST_URI'];

        $user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  	=> $employee->id,
            'title'  		=> $request['title'],
            'description'   => $request['description'],
			'date'  		=> $request['date'],
			'time1' 		=> $request['time1'],
			'time2' 		=> $request['time2'],			
        );

        $event = new Event();

		$event->saveEvent($data);

        /*
        $from = DateTime::createFromFormat('Y-m-d H:i', $event->date . ' ' . $event->time1 );
        $to = DateTime::createFromFormat('Y-m-d H:i',  $event->date . ' ' . $event->time2 );

        $link = Link::create($event->title, $from, $to)
            ->description($event->description)
            ->address('Svetonedeljska 18');
        
        // Generate a link to create an event on Google calendar
        echo $link->google();

        */
		session()->flash('success',  __('ctrl.data_save'));
		if($host == '/event') {
            return redirect()->route('events.index');
        } else {
            return redirect()->back();
        }
        
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
        $event = Event::find($id);
        $type = 'event';

        if(isset($request['type'])) {
            if($request['type'] == 'task') {
                $type = 'task';
            }
        }
        return view('Centaur::events.edit', ['event' => $event, 'type' => $type]);
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
        $event = Event::find($id);

        $data = array(
			'title'  		=> $request['title'],
			'date'  		=> $request['date'],
			'time1' 		=> $request['time1'],
			'time2' 		=> $request['time2'],
			'description'   => $request['description']
        );

        $event->updateEvent($data);
        
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
        $event = Event::find($id);
        $event->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
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

    public static function event_for_selected_day ($date) {
       
        $empl = Sentinel::getUser()->employee;
        $dataArr = array();

        if($empl) {
            
            $events = Event::where('employee_id', $empl->id)->get();
/*
            foreach($events as $event1) {
                array_push($dataArr, ['name' => "event", 'type' => __('calendar.event'), 'date' => $event1->date, 'title' => $event1->title]);
            }
*/
            $absences = Absence::where('approve',1)->get();
            
            foreach($absences as $absence) {
                
                $begin = new DateTime($absence->start_date);
                $end = new DateTime($absence->end_date);
                $end->setTime(0,0,1);
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                foreach ($period as $dan) {
                      if(date_format($dan,'Y-m-d') == $date) {  // ako je selektirani datum
                        array_push($dataArr, ['name' => $absence->absence['mark'],'type' => $absence->absence['name'], 'date' => date_format($dan,'Y-m-d'), 'employee' => $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name'], 'employee_id' => $absence->employee_id ]);
                    }
                }
            }

            $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
            
            $select_day = explode('-', $date);  //get from URL
            $dan_select = $select_day[2];
            $mj_select = $select_day[1];
            $god_select = $select_day[0];

            foreach($employees as $employee) {
                $dan_birthday = $god_select . '-' . date('m-d', strtotime($employee->b_day));
                if($dan_birthday == $date) {
                    array_push($dataArr, ['name' => 'birthday','type' => __('basic.birthday'), 'date' => $dan_birthday, 'employee' => $employee->user['first_name'] . ' ' . $employee->user['last_name'], 'employee_id' => $employee->id  ]);
                }
               
            } 
        }
        return $dataArr;
    }

    public function modal_event(Request $request)
    {
        $empl = Sentinel::getUser()->employee;
        $events = Event::where('employee_id', $empl->id)->get();
        
        return view('Centaur::all_event', ['dataArr_day' => $request['dataArr_day'], 'uniqueType' => $request['uniqueType'], 'dan' => $request['dan'], 'events' => $events  ]);
    }

    public static function getDataArr () {

        $empl = Sentinel::getUser()->employee;
        $events = Event::where('employee_id', $empl->id)->get();
        $dataArr = array();
        
        foreach($events as $event1) {
            array_push($dataArr, ['name' => "event", 'type' => __('calendar.event'), 'date' => $event1->date, 'title' => $event1->title]);
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
                    array_push($dataArr, ['name' => $absence->absence['mark'],'type' => $absence->absence['name'], 'date' => date_format($dan,'Y-m-d'), 'start_time' =>  $absence->start_time, 'end_time' =>  $absence->end_time, 'employee' => $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name']]);
                }
            }
        }

        $employees = Employee::where('id','<>',1)->where('checkout',null)->get();

        foreach($employees as $employee) {
            $dan = $god_select . '-' . date('m-d', strtotime($employee->b_day));
            array_push($dataArr, ['name' => 'birthday','type' => __('basic.birthday'), 'date' => $dan, 'employee' => $employee->user['first_name'] . ' ' . $employee->user['last_name'] ]);
        }

        return $dataArr;
    }
    
}
