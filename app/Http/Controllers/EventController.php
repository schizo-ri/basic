<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Task;
use App\Models\Employee;
use App\Models\Absence;
use App\Models\Locco;
use App\Models\Car;
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
    public function index(Request $request)
    {
        $empl = Sentinel::getUser()->employee;
        $permission_dep = array();
        if( isset($_GET['dan']) ) {
            $dan = $_GET['dan'];
        } else {
            $dan = date('Y-m-d');
        }
        $month = date('m',strtotime($dan) );
        $year = date('Y',strtotime($dan) );

        
        $dataArr = EventController::getDataArr($month, $year);
     
        $tasks = Task::whereMonth('date',$month )->get();
        $employees = Employee::where('checkout',null)->get('id');
       
        $cars = Car::get('registration');

        if($empl) {
            $events = Event::whereMonth('date',$month)->where('employee_id', $empl->id)->get();

            if( $empl->work && $empl->work->department && $empl->work->department->departmentRole ) {
                $permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
            }
        
            $start = new DateTime('00:00');
            $times = 24;

            for ($i = 0; $i < $times-1; $i++) {
                $hours_array[] = $start->add(new DateInterval('PT1H'))->format('H:i');
            }
    
            return view('Centaur::events.index',['dataArr'=> $dataArr,'employees'=>$employees,'events'=>$events,'tasks'=>$tasks, 'permission_dep' => $permission_dep,'hours_array' => $hours_array,'cars' => $cars]);
          
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
       

        return view('Centaur::events.create', ['time' => $time,'time2' => $time2, 'date' => $date ]);
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
			'date'  		=> $request['date'],
			'time1' 		=> $request['time1'],
			'time2' 		=> $request['time2'],
			'description'   => $request['description']
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
        $event = Event::find($id);
        
        return view('Centaur::events.show', ['event' => $event ]);
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
        
        /* session()->flash('success',  __('ctrl.data_edit')); */
	
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

    /* Broji dane izostanaka, sastanaka i rođendana*/
    public static function countDays ($dataArr, $dan) 
    {
        $dani_event= 0; 
        $dani_rodjendani = 0; 
        $dani_odmor = 0;
        
        foreach($dataArr as $arr) {
            if( $arr['date'] == $dan) {
                if($arr['name'] == 'event') {
                    $dani_event++;
                } 
                if($arr['name'] =='BOL' || $arr['name'] =='GO') {
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

    /* Vraća selektirani dan kao array */
    public static function selectedDay ($dan) {
        $select_day = '';
        $dan_select = '';
        $mj_select = '';
        $tj_select = date('W',strtotime($dan));
        $god_select = '';
       
        $select_day = explode('-',$dan);
       
        $week_day = date("D", strtotime($dan) );
        $month =  date("F",  strtotime($dan) );
        $dan_select = $select_day[2];
        $mj_select = $select_day[1];
        $god_select = $select_day[0];

        return ['week_day' => $week_day, 'month' => $month, 'dan_select' => $dan_select, 'tj_select' => $tj_select, 'mj_select' => $mj_select, 'god_select' => $god_select];
    }

    /* Vraća događanje, zadatke, rođendane i izostanke za selektorani dan */
    public static function event_for_selected_day ($date) 
    {
        $empl = Sentinel::getUser()->employee;
        $dataArr = array();

        $holidays = BasicAbsenceController::holidays_with_names();
        
        if(count($holidays) > 0) {
            foreach ($holidays as $key => $holiday) {
                if($date == $key )
                array_push($dataArr, ['name' => 'holiday', 'type' => __('basic.holidays'), 'date' => $key, 'title' => $holiday ]);
            }
        }

        $tasks = Task::whereDate('date', $date)->get();        
        if(count( $tasks)>0) {
            foreach($tasks as $task) {
                array_push($dataArr, ['name' => "task", 'type' => __('calendar.task'), 'date' => $task->date,'time1' => $task->time1, 'time2' => $task->time2, 'title' => $task->title, 'employee' => $task->employee->user['first_name'] . ' ' . $task->employee->user['last_name'], 'employee_id' => $task->employee_id, 'background' =>  $task->color, 'car' => $task->car['registration'] ]);
            }
        }     
       
        if($empl) {
            $events = Event::where('employee_id', $empl->id)->whereDate('date', $date)->get();
            if(count( $events)>0) {
                foreach($events as $event1) {
                    array_push($dataArr, ['name' => "event", 'type' => __('calendar.event'), 'date' => $event1->date,'time1' => $event1->time1, 'time2' => $event1->time2, 'title' => $event1->title, 'employee' => $event1->employee->user['first_name'] . ' ' . $event1->employee->user['last_name'], 'employee_id' => $event1->employee_id ]);
                }
            }          

            $absences = Absence::where('approve',1)->get();
            if(count($absences)>0) {
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
            }           

            $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
            
            $select_day = explode('-', $date);  //get from URL
            $dan_select = $select_day[2];
            $mj_select = $select_day[1];
            $god_select = $select_day[0];

            foreach($employees as $employee) {
                $dan_birthday = $god_select . '-' . date('m-d', strtotime($employee->b_day));
                if($dan_birthday == $date) {
                    array_push($dataArr, ['name' => 'birthday','type' => __('basic.birthday'), 'date' => $dan_birthday, 'employee' => $employee->user['first_name'] . ' ' . $employee->user['last_name'], 'employee_id' => $employee->id ]);
                }               
            } 
        }

        $loccos = Locco::whereDate('date', $date)->get();
        foreach($loccos as $locco) {
            if(date('Y-m-d', strtotime($locco->date)) == $date) {
                array_push($dataArr, ['name' => 'locco',
                                      'type' => __('basic.locco'), 
                                      'date' => $locco->date, 
                                      'employee' => $locco->employee->user['first_name'] . ' ' . $locco->employee->user['last_name'], 
                                      'employee_id' => $locco->employee->id, 
                                      'title' => $locco->destination, 
                                      'reg' => $locco->car['registration'] ]);
            }               
        } 

        return $dataArr;
    }

    /* Vraća događanje, zadatke, rođendane i izostanke */
    public static function getDataArr ($month, $year) 
    {
        $empl = Sentinel::getUser()->employee;
       
        $dataArr = array();
        
        $holidays = BasicAbsenceController::holidays_with_names();

        if(count($holidays) > 0) {
            foreach ($holidays as $date => $holiday) {
                array_push($dataArr, ['name' => 'holiday', 'type' => __('basic.holidays'), 'date' => $date, 'title' => $holiday ]);
            }
        }
        $tasks = Task::whereMonth('date',$month)->whereYear('date',$year)->get();
        if(count($tasks) > 0) {
            foreach($tasks as $task) {
                array_push($dataArr, ['name' => "task", 
                                      'type' => __('calendar.task'), 
                                      'date' => $task->date,
                                      'time1' => $task->time1, 
                                      'time2' => $task->time2, 
                                      'title' => $task->title, 
                                      'employee' => $task->employee->user['first_name'] . ' ' . $task->employee->user['last_name'], 
                                      'background' =>  $task->employee->color, 
                                      'car' => $task->car['registration'], 
                                      'employee_id' => $task->employee_id ]);
            }
        }

        $events = Event::whereMonth('date',$month)->whereYear('date',$year)->where('employee_id', $empl->id)->get();
        if(count($events) > 0) {
            foreach($events as $event1) {
                array_push($dataArr, ['name' => "event", 
                                      'type' => __('calendar.event'), 
                                      'date' => $event1->date,
                                      'time1' => $event1->time1, 
                                      'time2' => $event1->time2, 
                                      'title' => $event1->title, 
                                      'employee_id' => $event1->employee_id]);
            }
        }
        
        $absences = Absence::whereMonth('start_date', $month)->whereYear('start_date', $year)->where('approve',1)->get();
        $absences = $absences->merge(Absence::whereMonth('end_date', $month)->whereYear('end_date', $year)->where('approve',1)->get());

       /*  $today = date('Y-m-d');
        $select_day = explode('-',$today); 
        $dan_select = $select_day[2];
        $mj_select = $select_day[1];
        $god_select = $select_day[0]; */

        if (count($absences)>0) {
            foreach($absences as $absence) {
                $begin = new DateTime($absence->start_date);
                $end = new DateTime($absence->end_date);
                $end->setTime(0,0,1);
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                foreach ($period as $dan) {
                    if(date_format($dan,'Y') == $year) {  // ako je trenutna godina
                        array_push($dataArr, ['name' => $absence->absence['mark'],
                                              'type' => $absence->absence['name'], 
                                              'date' => date_format($dan,'Y-m-d'), 
                                              'start_time' =>  $absence->start_time, 
                                              'end_time' =>  $absence->end_time, 
                                              'employee' => $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name'], 
                                              'employee_id' => $absence->employee_id]);
                    }
                }
            }
        }

        $employees = Employee::whereMonth('b_day', $month)->where('id','<>',1)->where('checkout',null)->get();
        if(count($employees)>0) {
            foreach($employees as $employee) {
                $dan = $year . '-' . date('m-d', strtotime($employee->b_day));
                array_push($dataArr, ['name' => 'birthday',
                                      'type' => __('basic.birthday'), 
                                      'date' => $dan, 
                                      'employee' => $employee->user['first_name'] . ' ' . $employee->user['last_name'], 
                                      'employee_id' => $employee->id ]);
            }
        }
        $loccos = Locco::whereMonth('date', $month)->whereYear('date', $year)->get();
        $loccos = $loccos->merge(Locco::whereMonth('end_date', $month)->whereYear('end_date', $year)->get());
        if(count($loccos)>0) {
            foreach($loccos as $locco) {
                array_push($dataArr, ['name' => 'locco',
                                        'type' => __('basic.locco'), 
                                        'date' => $locco->date, 
                                        'employee' => $locco->employee ? $locco->employee->user['first_name'] . ' ' . $locco->employee->user['last_name'] : "", 
                                        'employee_id' => $locco->employee ?  $locco->employee->id : "", 
                                        'title' => $locco->destination , 
                                        'reg' => $locco->car['registration'] ]);
            }
        }

        return $dataArr;
    }

    /* View sire calendar  */
    public function side_calendar($dan) 
    {    
        $dataArr_day = EventController::event_for_selected_day( $dan );

        $uniqueType = array_unique(array_column($dataArr_day, 'type'));
       
        return view('side_calendar',['dataArr_day'=>$dataArr_day, 'uniqueType'=>$uniqueType]); 
    }

     /* View modal izostanci  */
     public function modal_event(Request $request)
     {
         $empl = Sentinel::getUser()->employee;
         $events = Event::where('employee_id', $empl->id)->get(); 
         
         $dataArr_day = array();
         $uniqueType= array();
         $dan = null;
 
         if ( $request['dataArr_day']) {
             $dataArr_day = $request['dataArr_day'];
         }
         if ( $request['uniqueType']) {
             $uniqueType = $request['uniqueType'];
         }
         if ( $request['dan']) {
             $dan = $request['dan'];
         }
 
         return view('Centaur::all_event', ['dataArr_day' => $dataArr_day, 'uniqueType' => $uniqueType, 'dan' => $dan]);     
     }
}
