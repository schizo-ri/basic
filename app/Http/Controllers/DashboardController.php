<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Models\Event;
use App\Models\Task;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Company;
use App\Models\Locco;
use App\Models\Setting;
use App\Models\WorkRecord;
use App\Models\Shortcut;
use App\Http\Controllers\BasicAbsenceController;
use Sentinel;
use DateTime;
use DB;
use Log;
use mysqli;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        if(Sentinel::check()) {
            $employee = Sentinel::getUser()->employee;
            $moduli = CompanyController::getModules();  //dohvaća module firme
           
            if($employee) {
                $data_absence = BasicAbsenceController::zahtjevi( $employee ); 
               
                //dohvaća dopuštenja odjela za korisnika
                $permission_dep = DashboardController::getDepartmentPermission();

                $posts = Post::PostToEmployee($employee)->take(5);
                foreach ($posts as $post) {
                    $profile = PostController::profile($post);
                    $post->post_comment = $profile['post_comment'];//zadnji komentar na poruku
                	$post->employee =  $post->employee;
                    $post->user_name = $profile['user_name']; // ime djelatnika kojem je poslana poruka a nije user 
                    $post->image_to_employee =  $profile['docs']; // profilna slika
                    $post->countComment = PostController::countComment($post);
                }

                if(isset($_GET['active_date']) && $_GET['active_date']) {
                    $date = $_GET['active_date'];
                } else {
                    $date = date('Y-m-d');
                }
                
                $events = Event::where('employee_id',$employee->id)->where('date', $date)->orderBy('date','DESC')->get();
                $tasks = Task::where('employee_id', $employee->id)->where('date', $date)->orderBy('date','DESC')->get();

                $profile_image = DashboardController::profile_image( $employee->id );
                $user_name =  DashboardController::user_name( $employee->id );					
                        
                $locco_active = Locco::where('employee_id', $employee->id)->where('status',0)->orderBy('date','ASC')->get();
                //Broj neodobrenih zahtjeva
                $count_requests =  AbsenceController::countRequest();
                //Broj nepročitanih poruka
                $countComment_all = PostController::countComment_all();
                $check = DashboardController::evidention_check();
                $shortcuts = Shortcut::where('employee_id', $employee->id )->get();
               
                return view('Centaur::dashboard',['locco_active' => $locco_active, 'posts' => $posts, 'events' => $events,'tasks' => $tasks,'moduli' => $moduli,'permission_dep' => $permission_dep,'employee' => $employee, 'data_absence' => $data_absence, 'profile_image' => $profile_image, 'user_name' => $user_name, 'count_requests' => $count_requests, 'countComment_all' => $countComment_all, 'check' => $check, 'shortcuts' => $shortcuts]);
            } else {
                return view('Centaur::dashboard',['moduli' => $moduli]);
            }
        } else {
            return view('welcome');
        }
    }
    
    public static function profile_image( $employee_id ) 
    {
        $image = '';
		$user_name = DashboardController::user_name( $employee_id );
       
        $path = 'storage/' . $user_name . "/profile_img/";
        
        if(file_exists($path)){
            $image = array_diff(scandir($path), array('..', '.', '.gitignore'));
        } else {
			$image = '';
        }

        return $image;
    }
    
    public static function user_name( $employee_id ) 
    {
        $user_name = '';
        $employee = Employee::where('id', $employee_id )->first();
		
		if( $employee ) {
			$user_name = explode('.',strstr($employee->email,'@',true));
        
			if(count($user_name) == 2) {
				$user_name = $user_name[1] . '_' . $user_name[0];
			} else {
				$user_name = $user_name[0];
			}
		}

        return $user_name;
    }
	
    public static function getDepartmentPermission () 
    {
		if(Sentinel::check()) {
            $employee = Sentinel::getUser()->employee;
            $permission_dep = array();

            if($employee && isset($employee->work) && $employee->work->department->departmentRole->isNotEmpty()) {
                $permission_dep = explode(',', count($employee->work->department->departmentRole) > 0 ? $employee->work->department->departmentRole->toArray()[0]['permissions'] : '');
            }
            
            return $permission_dep;	
        } 
    }
    
    public static function getUserDepartment () {
        if(Sentinel::check()) {
            $employee = Sentinel::getUser()->employee;
            $departments = Department::get();
            $user_department = array();
            
            if($employee && isset($employee->work) && $employee->work->department) {
                array_push($user_department, $employee->work->department->id);
                array_push($user_department, $departments->where('level1', 0)->first()->id);
            }
            return $user_department;	
        } 
    }

    public function openAdmin() 
    {    
        $moduli = CompanyController::getModules();
        
        return view('Centaur::admin_panel',['moduli' => $moduli]);
    }

    public function openAdminNew() 
    {    
        return view('Centaur::admin');
    }

    public static function evidention_check() 
    {
        $user = Sentinel::getUser();
        $employee = $user ->employee;
        $record = null;

        if( $employee ) {
            

            $record_yesterday = WorkRecord::where('employee_id', $employee->id)->whereDate('start', '<', date('Y-m-d'))->orderBy('start','DESC')->first();
            if( $record_yesterday && $record_yesterday['end'] == null ) {
                $start = $record_yesterday->start;
                $end = date('Y-m-d', strtotime( $start )) . ' 16:15:00';
                $data = array(
                    'end'  =>   $end,
                );
                $record_yesterday->updateWorkRecords($data);
            }
    
            $record = WorkRecord::where('employee_id', $employee->id)->whereDate('start', date('Y-m-d'))->first();
        
        }
       
        return $record;
    }

    
}
