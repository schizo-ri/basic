<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notice;
use App\Models\Event;
use App\Models\Employee;
use App\Models\Department;
use App\Http\Controllers\BasicAbsenceController;
use Sentinel;
use DateTime;

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
            $questionnaires = Questionnaire::where('status','1')->get();
            $employee = Sentinel::getUser()->employee;
            $notices = Notice::orderBy('created_at','DESC')->get();
            $departments = Department::get();
            //dohvaća module firme
            $moduli = CompanyController::getModules();
    
            $docs = '';
            if($employee) {
                $data_absence = array(
                    'zahtjevi' => BasicAbsenceController::zahtjevi( $employee )
                );
                
                //dohvaća dopuštenja odjela za korisnika
                try{
                    $permission_dep = explode(',', $employee->work->department->departmentRole->toArray()[0]['permissions']);
                } catch (Exception $e) {
                    $permission_dep = array();
                } 

                $posts = Post::where('employee_id',$employee->id)->orWhere('to_employee_id',$employee->id)->orderBy('updated_at','DESC')->get();
                $comments = Comment::orderBy('created_at','DESC')->get();
                $user_department = $employee->work->department->id;

                $date = new DateTime();
                $date->modify('-30 day');

                $events = Event::where('employee_id',$employee->id)->where('date','>=', $date->format('Y-m-d'))->orderBy('created_at','ASC')->get();

                return view('Centaur::dashboard',['questionnaires' => $questionnaires, 'posts' => $posts, 'comments' => $comments, 'notices' => $notices, 'user_department' => $user_department,'events' => $events,'departments' => $departments,'moduli' => $moduli,'permission_dep' => $permission_dep,'employee' => $employee, 'data_absence' => $data_absence]);
            } else {
                return view('Centaur::dashboard',['questionnaires' => $questionnaires, 'notices' => $notices,'departments' => $departments,'moduli' => $moduli,'docs' => $docs]);
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
	
	public static function getDepartmentPermission () {
		
		$employee = Sentinel::getUser()->employee;
		$departments = Department::get();
		$user_department = array();
		$permission_dep = array();

		if($employee) {
			array_push($user_department, $employee->work->department->id);

			array_push($user_department, $departments->where('level1',0)->first()->id);
			$permission_dep = explode(',', count($employee->work->department->departmentRole) > 0 ? $employee->work->department->departmentRole->toArray()[0]['permissions'] : '');
		}
		
		return $permission_dep;	
		
	}
		
}
