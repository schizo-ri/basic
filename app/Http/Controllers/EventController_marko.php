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
use DB;

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
       
        $selected = EventController::selectedDay( $dan );
      
        if($empl) {
            $events = Event::whereMonth('date',$selected['mj_select'])->where('employee_id', $empl->id)->get();
            foreach ( $events as $event ) {
                $event->week = date('W',strtotime($event->date));
            }
            if( $empl->work && $empl->work->department && $empl->work->department->departmentRole ) {
                $permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
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
        } else{
            $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        }
        $days_in_month = $daysInMonth[intval($selected['mj_select'])-1];
        $dataArr_day = EventController::event_for_selected_day( $dataArr, $dan );
     
        $uniqueType = array_unique(array_column($dataArr_day, 'type'));
        if(count($events)>0) {
            $events_day = $events->where('date', $dan);
        }
        $tasks_day = TaskController::task_for_selected_day( $dan );
        $tasks = Task::whereMonth('date', $selected['mj_select'] )->get();
        foreach ( $tasks as $task ) {
            $task->week = date('W',strtotime($task->date));
        }
    
        $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
       
        $cars = Car::get('registration');

        if($empl) {
            return view('Centaur::events.index',['dataArr'=> $dataArr,
                                                'dataArr_day'=> $dataArr_day,
                                                'days_in_month'=> $days_in_month,
                                                'employees'=>$employees,
                                                'events'=>$events,
                                                'tasks'=>$tasks, 
                                                'permission_dep' => $permission_dep,
                                                'hours_array' => $hours_array,
                                                'selected' => $selected,
                                                'selected_day' => $selected_day,
                                                'count_days' => $count_days,
                                                'tasks' => $tasks,
                                                'events' => $events,
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

    /*   */
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
        $select_day = explode('-',$dan);
       
        $week_day = date("D", strtotime($dan) );
        $month =  date("F",  strtotime($dan) );
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

       /*  
        $empl = Sentinel::getUser()->employee;
        $dataArr = array();

        $holidays = BasicAbsenceController::holidays_with_names();
        
        if(count($holidays) > 0) {
            foreach ($holidays as $key => $holiday) {
                if($date == $key )
                array_push($dataArr, ['name' => 'holiday', 'type' => __('basic.holidays'), 'date' => $key, 'title' => $holiday ]);
            }
        }

        $employees = Employee::where('id','<>',1)->where('checkout',null)->with('hasEvents')->with('hasLocco')->with('hasAbsences')->with('hasTasks')->get();
           
        $select_day = explode('-', $date);  //get from URL
        $dan_select = $select_day[2];
        $mj_select = $select_day[1];
        $god_select = $select_day[0];

        foreach($employees as $employee) {
            $dan_birthday = $god_select . '-' . date('m-d', strtotime($employee->b_day));
            if($dan_birthday == $date) {
                array_push($dataArr, ['name' => 'birthday','type' => __('basic.birthday'), 'date' => $dan_birthday, 'employee' => $employee->user['first_name'] . ' ' . $employee->user['last_name'], 'employee_id' => $employee->id ]);
            }

            $tasks = $employee->hasTasks->where('date', $date);    
            
            if(count( $tasks)>0) {
                foreach($tasks as $task) {
                    array_push($dataArr, ['name' => "task", 'type' => __('calendar.task'), 'date' => $task->date,'time1' => $task->time1, 'time2' => $task->time2, 'title' => $task->title, 'employee' => $task->employee->user['first_name'] . ' ' . $task->employee->user['last_name'], 'employee_id' => $task->employee_id, 'background' =>  $task->color, 'car' => $task->car['registration'] ]);
                }
            }

            $absences = $employee->hasAbsences;    
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
        
            $loccos =  $employee->hasLocco;  
           
            $loccos = $loccos->filter(function ($locco, $key) use ($date) {
                return date('Y-m-d',strtotime($locco->date)) == $date;
            });
         
            foreach($loccos as $locco) {
                array_push($dataArr, ['name' => 'locco',
                                        'type' => __('basic.locco'), 
                                        'date' => $locco->date, 
                                        'employee' => $locco->employee ? $locco->employee->user['first_name'] . ' ' . $locco->employee->user['last_name'] : '', 
                                        'employee_id' => $locco->employee->id, 
                                        'title' => $locco->destination, 
                                        'reg' => $locco->car['registration'] ]);
            } 
      
            if($empl) {
                $events = $employee->hasEvents->where('employee_id', $empl->id)->where('date', $date);  
                if(count( $events)>0) {
                    foreach($events as $event1) {
                        array_push($dataArr, ['name' => "event", 'type' => __('calendar.event'), 'date' => $event1->date,'time1' => $event1->time1, 'time2' => $event1->time2, 'title' => $event1->title, 'employee' => $event1->employee->user['first_name'] . ' ' . $event1->employee->user['last_name'], 'employee_id' => $event1->employee_id ]);
                    }
                }
            }
        }
 */
        return $dataArr;
    }

    /* Vraća događanje, zadatke, rođendane i izostanke */
    public static function getDataArr ( $month, $year ) 
    {
        $time_start = microtime(true);
        $empl = Sentinel::getUser()->employee;
        $dataArr = array();
		//**MARKO DODAO**
        $dataInitial = array();
		//****
        $holidays = BasicAbsenceController::holidays_with_names();

		//**MARKO MAKNUO**
        /*if(count($holidays) > 0) {
            foreach ($holidays as $date => $holiday) {
                array_push($dataArr, ['name' => 'holiday', 'type' => __('basic.holidays'), 'date' => $date, 'title' => $holiday ]);
            }
        }*/
		//****
		
		//**MARKO DODAO**
		if(count($holidays) > 0)
			$dataArr = array_merge($dataInitial, $holidays);
		//****
		
		$birthdays = DB::select("SELECT
								'birthday' AS name,
								'".__('basic.birthday')."' AS type,
								CONCAT('".$year."-', DATE_FORMAT(employees.b_day, '%m-%d')) AS date,
								CONCAT(users.first_name, ' ', users.last_name) AS employee,
								employees.id AS employee_id
							FROM employees
							LEFT JOIN users ON users.id = employees.user_id
							WHERE employees.id != 1 AND employees.checkout IS NULL AND MONTH(employees.b_day) = ".$month."");
							
		$birthdays = json_decode(json_encode((array) $birthdays), true);
		
		/*$birthdays = Employee::where("employees.id", "<>", 1)->where("employees.checkout", null)->leftJoin("users", "employees.user_id", "=", "users.id")->whereMonth("employees.b_day", $month)
					->selectSub("'birthday'", "name")
					->selectSub("'".__('basic.birthday')."'", "type")
					->selectSub("CONCAT('".$year."-', DATE_FORMAT(employees.b_day, '%m-%d'))", "date")
					->selectSub("CONCAT(users.first_name, ' ', users.last_name)", "employee")
					->selectSub("employees.id", "employee_id")
					->get()->toArray();*/
		
		if(count($birthdays) > 0)
			$dataArr = array_merge($dataArr, $birthdays);
			
		$tasks = DB::select("SELECT
								'task' AS name,
								'".__('calendar.task')."' AS type,
								tasks.id AS id,
								tasks.date AS date,
								tasks.time1 AS time1,
								tasks.time2 AS time2,
								tasks.title AS title,
								CONCAT(users.first_name, ' ', users.last_name) AS employee,
								employees.color AS background,
								cars.registration AS car,
								employees.id AS employee_id
							FROM employees
							LEFT JOIN users ON users.id = employees.user_id
							LEFT JOIN tasks ON tasks.employee_id = employees.id
							LEFT JOIN cars ON cars.id = tasks.car_id
							WHERE employees.id != 1 AND employees.checkout IS NULL AND MONTH(tasks.date) = ".$month." AND YEAR(tasks.date) = ".$year."");
							
		$tasks = json_decode(json_encode((array) $tasks), true);
		
		/*$tasks = 	Employee::where("employees.id", "<>", 1)->where("employees.checkout", null)->leftJoin("users", "employees.user_id", "=", "users.id")->leftJoin("tasks", "employees.id", "=", "tasks.employee_id")->leftJoin("cars", "tasks.car_id", "=", "cars.id")->whereMonth("tasks.date", $month)->whereYear("tasks.date", $year)
					->selectSub("'task'", "name")
					->selectSub("'".__('calendar.task')."'", "type")
					->selectSub("tasks.id", "id")
					->selectSub("tasks.date", "date")
					->selectSub("tasks.time1", "time1")
					->selectSub("tasks.time2", "time2")
					->selectSub("tasks.title", "title")
					->selectSub("CONCAT(users.first_name, ' ', users.last_name)", "employee")
					->selectSub("employees.color", "background")
					->selectSub("cars.registration", "car")
					->selectSub("employees.id", "employee_id")					  
					->get()->toArray();*/
		
		if(count($tasks) > 0)
			$dataArr = array_merge($dataArr, $tasks);
		
		
		$events = DB::select("SELECT
								'event' AS name,
								'".__('calendar.event')."' AS type,
								events.id AS id,
								events.date AS date,
								events.time1 AS time1,
								events.time2 AS time2,
								events.title AS title,
								CONCAT(users.first_name, ' ', users.last_name) AS employee,
								employees.id AS employee_id
							FROM employees
							LEFT JOIN users ON users.id = employees.user_id
							LEFT JOIN events ON events.employee_id = employees.id
							WHERE employees.id != 1 AND employees.checkout IS NULL AND MONTH(events.date) = ".$month." AND YEAR(events.date) = ".$year."");
							
		$events = json_decode(json_encode((array) $events), true);
		
		/*$events = 	Employee::where("employees.id", "<>", 1)->where("employees.checkout", null)->leftJoin("users", "employees.user_id", "=", "users.id")->leftJoin("events", "employees.id", "=", "events.employee_id")->whereMonth("events.date", $month)->whereYear("events.date", $year)
					->selectSub("'event'", "name")
					->selectSub("'".__('calendar.event')."'", "type")
					->selectSub("events.id", "id")
					->selectSub("events.date", "date")
					->selectSub("events.time1", "time1")
					->selectSub("events.time2", "time2")
					->selectSub("events.title", "title")
					->selectSub("CONCAT(users.first_name, ' ', users.last_name)", "employee")
					->selectSub("employees.id", "employee_id")					  
					->get()->toArray();*/
		
		if(count($events) > 0)
			$dataArr = array_merge($dataArr, $events);

		$absences = DB::select("WITH RECURSIVE cte AS (
                                    SELECT
									absence_types.mark AS name,
									absence_types.name AS type,
									absences.start_date AS date,
									absences.end_date AS _du,
									absences.start_time AS start_time,
									absences.end_time AS end_time,
									CONCAT(users.first_name, ' ', users.last_name) AS employee,
									employees.id AS employee_id
								FROM employees
								LEFT JOIN users ON users.id = employees.user_id
								LEFT JOIN absences ON absences.employee_id = employees.id
								LEFT JOIN absence_types ON absence_types.id = absences.type
								WHERE employees.id != 1 AND employees.checkout IS NULL AND absences.approve IS NOT NULL AND absences.approve = 1 AND 
								((MONTH(absences.start_date) = ".$month." AND YEAR(absences.start_date = ".$year.")) OR (MONTH(absences.end_date) = ".$month." AND YEAR(absences.end_date) = ".$year."))
								UNION ALL
								SELECT 
									name,
									type,
									DATE_ADD(cte.date, INTERVAL 1 DAY),
									_du,
									start_time,
									end_time,
									employee,
									employee_id
								FROM cte 
								WHERE DATE_ADD(date, INTERVAL 1 DAY) <= _du
							)
							SELECT 
								name,
								type,
								DATE_FORMAT(date, '%Y-%m-%d') AS date,
								start_time,
								end_time,
								employee,
								employee_id
							FROM cte
							ORDER BY employee_id, date ASC");
							
		$absences = json_decode(json_encode((array) $absences), true);
		
		if(count($absences) > 0)
			$dataArr = array_merge($dataArr, $absences);
		
		$loccos = DB::select("SELECT
								'locco' AS name,
								'".__('basic.locco')."' AS type,
								loccos.date AS date,
								CONCAT(users.first_name, ' ', users.last_name) AS employee,
								employees.id AS employee_id,
								loccos.destination AS title,
								cars.registration AS reg
							FROM employees
							LEFT JOIN users ON users.id = employees.user_id
							LEFT JOIN loccos ON loccos.employee_id = employees.id
							LEFT JOIN cars ON cars.id = loccos.car_id
							WHERE employees.id != 1 AND employees.checkout IS NULL AND 
							((MONTH(loccos.date) = ".$month." AND YEAR(loccos.date = ".$year.")) OR (MONTH(loccos.end_date) = ".$month." AND YEAR(loccos.end_date) = ".$year."))");
							
		$loccos = json_decode(json_encode((array) $loccos), true);
		
		/*$loccos = Employee::where("employees.id", "<>", 1)->where("employees.checkout", null)->leftJoin("users", "employees.user_id", "=", "users.id")->leftJoin("loccos", "employees.id", "=", "loccos.employee_id")->leftJoin("cars", "loccos.car_id", "=", "cars.id")->where(function ($query) use ($month, $year) {$query->whereMonth("loccos.date", $month)->whereYear("loccos.date", $year);})->orWhere(function($query) use ($month, $year) {$query->whereMonth("loccos.end_date", $month)->whereYear("loccos.end_date", $year);})
					->selectSub("'locco'", "name")
					->selectSub("'".__('basic.locco')."'", "type")
					->selectSub("loccos.date", "date")	
					->selectSub("CONCAT(users.first_name, ' ', users.last_name)", "employee")
					->selectSub("employees.id", "employee_id")
					->selectSub("loccos.destination", "title")
					->selectSub("cars.registration", "reg")	
					->get()->toArray();*/
		
		if(count($loccos) > 0)
			$dataArr = array_merge($dataArr, $loccos);
				
		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start);
	
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
        dd($request['dataArr_day']);
        return view('Centaur::all_event', ['dataArr_day' => $dataArr_day, 'uniqueType' => $uniqueType, 'dan' => $dan]);     
     }
}
