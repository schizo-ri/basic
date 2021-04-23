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
/* use Spatie\CalendarLinks\Link; */
/* use DiegoSouza\Zimbra\Facades\Zimbra; */
/* use DiegoSouza\Zimbra\Facades\Zimbra; */
use DB;
use Log;

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
       /*  $result = Zimbra::getAllCos();
        dd(  $result ); */
     
        $empl = Sentinel::getUser()->employee;
        $events = collect();

        if( isset($_GET['dan']) ) {
            $dan = $_GET['dan'];
        } else {
            $dan = date('Y-m-d');
        }
       
        $selected = EventController::selectedDay( $dan );
        if($empl) {
            if( Sentinel::inRole('administrator')) {
                $events = Event::whereMonth('date', $selected['mj_select'])->where('status', 0 )->get();
                $events =  $events->merge(Event::whereMonth('date', $selected['mj_select'])->where('status', 1)->where('employee_id', Sentinel::getUser()->employee->id  )->get());
            } else {
                $events_temp = Event::whereMonth('date', $selected['mj_select'])->get();
                foreach ($events_temp as $event) {
                    if( count ( $event->employee->hasEmployeeDepartmen  ) > 0 ) {
                        foreach ($event->employee->hasEmployeeDepartmen as $employeeDepartmenent ) {
                            if(  ! $events->contains( $event ) && 
                                ( ($employeeDepartmenent->department->employee_id == Sentinel::getUser()->employee->id && $event->status == 0 ) || 
                                ($event->employee_id == Sentinel::getUser()->employee->id) || 
                                ( $employeeDepartmenent->department->roofLevel->employee_id == Sentinel::getUser()->employee->id && $event->status == 0) ) ) {
                                $events = $events->push($event);
                            }
                        }
                    }
                }
            }
            /* dd( $events); */
            foreach ( $events as $event ) {
                $event->week = date('W',strtotime($event->date));
            }
           
            $start = new DateTime('00:00');
            $times = 24;

            for ($i = 0; $i < $times-1; $i++) {
                $hours_array[] = $start->add(new DateInterval('PT1H'))->format('H:i');
            }
        }

        $dataArr = EventController::getDataArr($selected['mj_select'], $selected['god_select']);
       
        $count_days = EventController::countDays($dataArr, $dan);
    
        $selected_day = $selected['god_select'] .'-'. $selected['mj_select'] .'-'. $selected['dan_select'];
        
        //	$days_in_month = cal_days_in_month(CAL_GREGORIAN, $selected['mj_select'],$selected['god_select']);  // broj dana u mjesecu
        if ($selected['god_select']%4 == 0) {
            $daysInMonth = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        }else{
            $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        }
        if($selected['mj_select']) {
            $mj_select = $selected['mj_select'];
        } else {
            $mj_select = date('m');
        }
        try {
            $days_in_month = $daysInMonth[intval($mj_select)-1];
        } catch (\Throwable $th) {
            $days_in_month = $daysInMonth[date('m')-1];
        }
        
        $dataArr_day = EventController::event_for_selected_day( $dataArr, $dan );
     
        $uniqueType = array_unique(array_column($dataArr_day, 'type'));
        if(count($events)>0) {
            $events_day = $events->where('date', $dan);
        }
     
        $tasks = Task::whereMonth('start_date', $mj_select )->get();
        foreach ( $tasks as $task ) {
            $task->week = date('W',strtotime($task->start_date));
        }
       
        $employees = Employee::employees_firstNameASC();
        $cars = Car::get('registration');

        if($empl) {
            return view('Centaur::events.index',['dataArr'=> $dataArr,
                                                'dataArr_day'=> $dataArr_day,
                                                'days_in_month'=> $days_in_month,
                                                'employees'=>$employees,
                                                'events'=>$events,
                                                'tasks'=>$tasks,
                                                'hours_array' => $hours_array,
                                                'selected' => $selected,
                                                'selected_day' => $selected_day,
                                                'count_days' => $count_days,
                                                'uniqueType' => $uniqueType,
                                                'cars' => $cars]);
          
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
    public function store (EventRequest $request)
    {
        $host = $_SERVER['REQUEST_URI'];

        $employee = Sentinel::getUser()->employee;

        $data = array(
			'employee_id'  	=> $employee->id,
			'title'  		=> $request['title'],
			'date'  		=> $request['date'],
			'time1' 		=> $request['time1'],
			'time2' 		=> $request['time2'],
			'description'   => $request['description'],
			'status'        => $request['status']
        );

        $event = new Event();

		$event->saveEvent($data);
        
        /* $from = DateTime::createFromFormat('Y-m-d H:i', $event->date . ' ' . $event->time1 );
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

    /*   Registracija vozila zapisana kao event*/
    public function store_event ( $id )
    {
        $car = Car::find( $id );
        $employee = Sentinel::getUser()->employee;
        $today = new DateTime();
        $today->modify('7 days');
        
        $data = array(
			'employee_id'  	=> $employee->id,
			'title'  		=> "Registracija vozila " . $car->registration,
			'date'  		=> $today->format('Y-m-d'),
			'time1' 		=> '08:00',
			'time2' 		=> '09:00',
			'description'   => "Registracija vozila " . $car->registration,
			'status'        => 0
        );

        $event = new Event();

        $event->saveEvent($data);

        session()->flash('success', "Događaj je spremljen u kalendar");

        return redirect()->route('dashboard');

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
			'description'   => $request['description'],
            'status'        => $request['status']
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
        $select_day = explode('-',$dan);
        
        setlocale(LC_TIME, "hr_HR");
     
        $week_day = iconv('ISO-8859-2', 'UTF-8',strftime("%a", strtotime($dan)));
       
        $month =  iconv('ISO-8859-2', 'UTF-8',strftime("%B", strtotime($dan)));
        $dan_select = $select_day[2];
        $tj_select = date('W',strtotime($dan));
        $mj_select = $select_day[1];
        $god_select = $select_day[0];
     
        return ['week_day' => $week_day, 'month' => $month, 'dan_select' => $dan_select, 'tj_select' => $tj_select, 'mj_select' => $mj_select, 'god_select' => $god_select];
    }

    /* Vraća događanje, zadatke, rođendane i izostanke za selektorani dan */
    public static function event_for_selected_day ($dataArr, $date) 
    {
        $dataArr = array_filter($dataArr, function($v, $k) use ( $date ) {
            return in_array($date, $v);
        }, ARRAY_FILTER_USE_BOTH);

        return $dataArr;
    }

    /* Vraća događanje, zadatke, rođendane i izostanke */
    public static function getDataArr ( $month, $year ) 
    {
        $empl = Sentinel::getUser()->employee;
        $dataArr = array();
        $holidays = BasicAbsenceController::holidays_with_names();
        $dataInitial = array();
         /*   
        if(count($holidays) > 0)
        $dataArr = array_merge($dataInitial, $holidays); */

         if(count($holidays) > 0) {
            foreach ($holidays as $date => $holiday) {
                array_push($dataArr, ['name' => 'holiday', 'type' => __('basic.holidays'), 'date' => $date, 'title' => $holiday ]);
            }
        }
    
        $employees = Employee::where('id','<>',0)->where('checkout',null)->with('hasEvents')->with('hasLocco')->with('hasAbsences')->with('hasTasks')->get();
        
        if(count($employees)>0) {  
            foreach($employees as $employee) {
                if(date('m',strtotime($employee->b_day)) == $month ) {
                    $dan = $year . '-' . date('m-d', strtotime($employee->b_day));
                    
                    array_push($dataArr, ['name' => 'birthday',
                                          'type' => __('basic.birthday'), 
                                          'date' => $dan, 
                                          'employee' => $employee->user['first_name'] . ' ' . $employee->user['last_name'], 
                                          'employee_id' => $employee->id ]);
                }
                if(date('Y-m',strtotime($employee->lijecn_pregled)) ==  $year . '-' . $month ) {
                    $dan = date('Y-m-d', strtotime($employee->lijecn_pregled));
                    array_push($dataArr, ['name' => 'liječnički',
                                          'type' =>  'Liječnički pregled', 
                                          'date' => $dan, 
                                          'employee' => $employee->user['first_name'] . ' ' . $employee->user['last_name'], 
                                          'employee_id' => $employee->id ]);
                }
                $tasks = $employee->hasEmployeeTask;
                $EmployeeTasks = $tasks->filter(function ($task, $key) use ( $month, $year) {
                    return date('m',strtotime($task->created_at)) == $month && date('Y',strtotime($task->created_at)) == $year;
                });
               
                if(count($EmployeeTasks) > 0) {
                    foreach($EmployeeTasks as $EmployeeTask) {
                        array_push($dataArr, ['name' => "task", 
                                              'type' => __('calendar.task'), 
                                              'id' => $EmployeeTask->id,
                                              'date' => date('Y-m-d',strtotime($EmployeeTask->created_at)),
                                              'time1' => $EmployeeTask->task->time1, 
                                              'time2' => $EmployeeTask->task->time2, 
                                              'title' => $EmployeeTask->task->task . ': ' . $EmployeeTask->task->description, 
                                              'employee' => $EmployeeTask->employee->user['first_name'] . ' ' . $EmployeeTask->employee->user['last_name'], 
                                              'background' =>  $EmployeeTask->employee->color, 
                                              'car' => $EmployeeTask->car['registration'], 
                                              'employee_id' => $EmployeeTask->employee_id ]);
                    }
                }
               
                $events = $employee->hasEvents;
               
                $events = $events->filter(function ($event, $key) use ( $month, $year ) {
                    return date('m',strtotime($event->date)) == $month && date('Y',strtotime($event->date)) == $year;
                });
        
                if(count($events) > 0) {
                    foreach($events as $event1) {
                        array_push($dataArr, ['name' => "event", 
                                              'type' => __('calendar.event'), 
                                              'date' => $event1->date,
                                              'id' => $event1->id,
                                              'time1' => $event1->time1, 
                                              'time2' => $event1->time2, 
                                              'title' => $event1->title, 
                                              'employee' => $event1->employee->user['first_name'] . ' ' . $event1->employee->user['last_name'], 
                                              'employee_id' => $event1->employee_id]);
                    }
                }
              
                $absences = $employee->hasAbsences;
                $absences = $absences->filter(function ($absence, $key) use ( $month, $year ) {
                    return (date('m',strtotime($absence->start_date)) == $month && date('Y',strtotime($absence->start_date)) == $year) || (date('m',strtotime($absence->end_date)) == $month && date('Y',strtotime($absence->end_date)) );
                });
        
                if (count($absences)>0) {
                    foreach($absences->where('approve',1) as $absence) {
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
              
                $loccos = $employee->hasLocco;  
                   
                $loccos = $loccos->filter(function ($locco, $key) use ( $month, $year) {
                    return (date('m',strtotime($locco->date)) == $month && date('Y',strtotime($locco->date)) ==  $year) || (date('m',strtotime($locco->end_date)) ==  $month && date('Y',strtotime($locco->end_date)) ==  $year);
                });
        
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
            }
        }
      

        /* $birthdays = Employee::leftJoin('users','employees.user_id','users.id')
                            ->select('employees.id AS employee_id')
                            ->selectRaw("concat(users.first_name, ' ', users.last_name) as employee")
                            ->selectRaw("concat( $year,'-',DATE_FORMAT(employees.b_day, '%m-%d')) as date")
                            ->selectRaw("concat('birthday') AS name")
                            ->selectRaw("concat('Rođendan') AS type")
                            ->where('employees.id','<>',0)
                            ->where('employees.checkout',null)
                            ->whereMonth('employees.b_day',$month)->get();
        if(count($birthdays) > 0)
            $dataArr = array_merge($dataArr, $birthdays->toArray());
            
        $lijecn_pregled = Employee::leftJoin('users','employees.user_id','users.id')
            ->select('employees.id AS employee_id')
            ->selectRaw("concat(users.first_name, ' ', users.last_name) as employee")
            // ->selectRaw("concat("employees.lijecn_pregled") as date")
            ->selectRaw("concat('liječnički') AS name")
            ->selectRaw("concat('Liječnički pregled') AS type")
            ->where('employees.id','<>',0)
            ->where('employees.checkout',null)
            ->whereMonth('employees.lijecn_pregled', $month)
            ->whereYear('employees.lijecn_pregled', $year)->get();
            dd( $lijecn_pregled);
        if(count($lijecn_pregled) > 0)
            $dataArr = array_merge($dataArr, $lijecn_pregled->toArray());    
            
        $tasks = Employee::leftJoin('users','employees.user_id','users.id')
                        ->leftJoin('tasks','tasks.employee_id','employees.id')
                        ->leftJoin('cars','cars.id','tasks.car_id')
                        ->select('employees.id AS employee_id','tasks.id AS id','tasks.start_date AS date', 'tasks.time1 AS time1','tasks.time2 AS time2','tasks.task AS title','employees.color AS background', 'cars.registration AS car')
                        ->selectRaw("concat('task') AS name")
                        ->selectRaw("concat('Zadatak') AS type")
                        ->selectRaw("concat(users.first_name, ' ', users.last_name) as employee")
                        ->where('employees.id','<>',0)
                        ->where('employees.checkout',null)
                        ->whereMonth('tasks.start_date',$month)
                        ->whereYear('tasks.start_date',$year)->get();
        if(count($tasks) > 0)
            $dataArr = array_merge($dataArr, $tasks->toArray());

        $events = Employee::leftJoin('users','employees.user_id','users.id')
                    ->leftJoin('events','events.employee_id','employees.id')
                    ->select('employees.id AS employee_id','events.id AS id','events.date AS date', 'events.time1 AS time1','events.time2 AS time2','events.title AS title')
                    ->selectRaw("concat('event') AS name")
                    ->selectRaw("concat('Događaj') AS type")
                    ->selectRaw("concat(users.first_name, ' ', users.last_name) as employee")
                    ->where('employees.id','<>',0)
                    ->where('employees.checkout',null)
                    ->whereMonth('events.date',$month)
                    ->whereYear('events.date',$year)->get();
        
        if(count($events) > 0)
            $dataArr = array_merge($dataArr, $events->toArray());  
       
        $loccos = Employee::leftJoin('users','employees.user_id','users.id')
                        ->leftJoin('loccos','loccos.employee_id','employees.id')
                        ->leftJoin('cars','cars.id','loccos.car_id')
                        ->select('employees.id AS employee_id','loccos.date AS date','cars.registration AS reg','loccos.destination AS title')
                        ->selectRaw("concat('locco') AS name")
                        ->selectRaw("concat('Locco vožnja') AS type")
                        ->selectRaw("concat(users.first_name, ' ', users.last_name) as employee")
                        ->where('employees.id','<>',0)
                        ->where('employees.checkout',null)
                        ->whereMonth('loccos.date',$month)
                        ->whereYear('loccos.date',$year)->get();
        if(count($loccos) > 0)
            $dataArr = array_merge($dataArr, $loccos->toArray()); */

        return $dataArr;
    }

    /* View sire calendar  */
    public function side_calendar($dan) 
    {    
        return view('side_calendar'); 
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
