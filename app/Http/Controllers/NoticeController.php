<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NoticeRequest;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EmailingController;
use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeStatistic;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Template;
use Sentinel;
use App\Mail\NoticeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Log;

class NoticeController extends Controller
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
    public function index()
    {
        $notices = Notice::orderBy('created_at','DESC')->get();
        $departments = Department::get();

        $empl = Sentinel::getUser()->employee;
        $permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		}
        
        return view('Centaur::notices.index', ['notices' => $notices,'departments' => $departments, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments0 = Department::where('level1',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level1',1)->orderBy('name','ASC')->get();
        $departments2 = Department::where('level1',2)->orderBy('name','ASC')->get();
        $templates = Template::get();

        return view('Centaur::notices.create', ['templates' => $templates, 'departments0' => $departments0, 'departments1' => $departments1, 'departments2' => $departments2]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( ! isset($request['to_department'])) {
            $to_department_id = array( Department::where('level',0)->first()->id );
        }
   
        if(Sentinel::getUser()->employee) {
            $employee_id = Sentinel::getUser()->employee->id;
                
            if($request['to_department']) {
                $to_department_id = implode(',', $request['to_department']);
                if(isset($request['schedule_date'])) {
                    if($request['schedule_time'] != null) {
                        $shedule = $request['schedule_date'] . ' ' . $request['schedule_time'];
                    } else {
                        $shedule = $request['schedule_date'] . ' 08:00';
                    }
                } else {
                    $shedule = date('Y-m-d') . ' 08:00';
                }
                
                $data1 = array(
                    'employee_id'   	=> $employee_id,
                    'to_department'     => $to_department_id,
                    'schedule_date'     => $shedule,
                    'title'  			=> $request['title'],
                    'notice'  			=> $request['text_html'],
                    'text_json'  		=> $request['text_json']
                );
            
                $notice1 = new Notice();
                $notice1->saveNotice($data1);
                
                $now = date('Y-m-d H:i');

                /* ***************************  posebna slika  ******************************** */

                if(isset($request['fileToUpload'])) {
                    $target_dir = 'storage/'; 
                    if(!file_exists($target_dir)){
                        mkdir($target_dir);
                    }
                    $target_dir = 'storage/notice/'; 
                    // Create directory
                    if(!file_exists($target_dir)){
                        mkdir($target_dir);
                    }
                    $target_dir = 'storage/notice/' . $notice1->id . '/';
                    // Create directory
                    if(!file_exists($target_dir)){
                        mkdir($target_dir);
                    }

                    $target_file = $target_dir . '/' . basename($_FILES["fileToUpload"]["name"]); //$target_file specifies the path of the file to be uploaded
                    if(isset($request['fileToUpload']) && file_exists($target_file)){
                        array_map('unlink', glob($target_file)); // ako postoji file - briše ga
                        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 
                    } 
        
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
                    // Check if image file is a actual image or fake image

                    // Check if file already exists
                    if (file_exists($target_file)) {
                        return redirect()->back()->with('error',  __('ctrl.file_exists'));  
                        $uploadOk = 0;
                    }
                    
                    /* Check file size*/
                    if ($_FILES["fileToUpload"]["size"] > 5000000) {
                        $uploadOk = 0;
                        return redirect()->back()->with('error',  __('ctrl.file_toolarge'));  
                    }
                    /* Allow certain file formats */
                    if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                        $uploadOk = 1;
                    } else {
                        $uploadOk = 0;
                        return redirect()->back()->with('error', __('ctrl.allow') . ' jpg, png, pdf, gif');  
                    }
                    if($imageFileType == "exe" || $imageFileType == "bin") {
                        $uploadOk = 0;
                        return redirect()->back()->with('error',  __('ctrl.not_allow'));  
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        return redirect()->back()->with('error', __('ctrl.not_uploaded')); 
                    // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            if($request['schedule_set'] == 0 || strtotime($now) >= strtotime($notice1->schedule_date) ) {
                                $all_dep_employee = array();
                                foreach($request['to_department'] as $department_id) {
                                    $departments_employees = Department::allDepartmentsEmployeesEmail( $department_id);
                                    $all_dep_employee = array_merge($all_dep_employee, $departments_employees);
                                }
                                Log::info($all_dep_employee);
                                foreach (array_unique($all_dep_employee) as $mail) {
                                    Mail::to($mail)->send(new NoticeMail($notice1));
                                }   
                                /*   try {               
                                } catch (\Throwable $th) {
                                    $message = session()->flash('success',  __('emailing.not_send'));
                                    return redirect()->back()->withFlashMessage($message);
                                } */
                            }
                        
                            return redirect()->back()->with('success', __('ctrl.notice_saved'));
                        //  return redirect()->back()->with('success',"The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
                        } else {
                            return redirect()->back()->with('error', __('ctrl.notice_error')); 
                        }
                    }
                } 

                /* ************************* SEND MAIL *********************************** */
        
                if($request['schedule_set'] == 0 || strtotime($now) >= strtotime($notice1->schedule_date) ) {
                    $all_dep_employee = array();
                    
                    foreach($request['to_department'] as $department_id) {
                        $departments_employees = Department::allDepartmentsEmployeesEmail( $department_id);
                        $all_dep_employee = array_merge($all_dep_employee, $departments_employees);
                    }
                    Log::info($all_dep_employee);
                    foreach (array_unique($all_dep_employee) as $mail) {
                       Mail::to($mail)->send(new NoticeMail($notice1));
                    }   
                    /*   try {               
                    } catch (\Throwable $th) {
                        $message = session()->flash('success',  __('emailing.not_send'));
                        return redirect()->back()->withFlashMessage($message);
                    } */
                }
               
                $message = session()->flash('success', __('ctrl.notice_saved'));

                return redirect()->back()->withFlashMessage($message);
            } else {
                $message = session()->flash('error', "Nije dodijeljen odjel");
                return redirect()->back()->withFlashMessage($message);
            }
        } else {
            $message = session()->flash('error', __('ctrl.path_not_allow') . ', ' .  __('ctrl.notice_only_employee'));
            return redirect()->back()->withFlashMessage($message);
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
        $notice = Notice::find($id);
        $employee = Sentinel::getUser()->employee;
        if( $employee) {
            $employee_id =  $employee->id;

            $permission_dep = explode(',', count($employee->work->department->departmentRole) > 0 ? $employee->work->department->departmentRole->toArray()[0]['permissions'] : '');
            
            if( $notice && ! NoticeStatistic::where('notice_id', $notice->id)->where('employee_id',  $employee_id)->first() ) {
                $data = array(
                    'employee_id'   => $employee_id,
                    'notice_id'     => $notice->id,
                    'status'  		=> 1
                );
                
                $statistic = new NoticeStatistic();
                $statistic->saveStatistic($data);
                
                $notice_statistic = NoticeStatistic::where('notice_id', $notice->id)->get();
                $count_statistic = count( $notice_statistic);
                $employees = Employee::employees_firstNameASC();
                $count_employees = count($employees);
                $statistic = $count_statistic /  $count_employees *100 ;
            } else {
                $statistic = 0;
            }
        } else {
            $permission_dep = array();
            $statistic = 0;
        }
      
        
        return view('Centaur::notices.show', ['notice' => $notice,'permission_dep' => $permission_dep, 'statistic' => $statistic]);
    }

   /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notice = Notice::find($id);
        $departments = explode(',', $notice->to_department );
        $templates = Template::get();

        $departments0 = Department::where('level1',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level1',1)->orderBy('name','ASC')->get();
        $departments2 = Department::where('level1',2)->orderBy('name','ASC')->get();

        return view('Centaur::notices.edit', ['notice' => $notice, 'templates' => $templates, 'departments' => $departments, 'departments0' => $departments0, 'departments1' => $departments1, 'departments2' => $departments2]);
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
        $notice1 = Notice::find($id);

        if(Sentinel::getUser()->employee) {
            $employee_id = Sentinel::getUser()->employee->id;
            $to_department_id = implode(',', $request['to_department']);
            if(isset($request['schedule_date'])) {
                if($request['schedule_time'] != null) {
                    $shedule = $request['schedule_date'] . ' ' . $request['schedule_time'];
                } else {
                    $shedule = $request['schedule_date'] . ' 08:00';
                }
            } else {
                $shedule = date('Y-m-d') . ' 08:00';
            }

            $data1 = array(
                'employee_id'   	=> $employee_id,
                'to_department'     => $to_department_id,
                'schedule_date'     => $shedule,
                'title'  			=> $request['title'],
                'notice'  			=> $request['text_html'],
                'text_json'  		=> $request['text_json']
            );
           

            $notice1->updateNotice($data1);
            
            $now = date('Y-m-d H:i');

            /* ***************************  posebna slika  ******************************** */

            if(isset($request['fileToUpload'])) {
                $target_dir = 'storage/notice/'; 
                
                // Create directory
                if(!file_exists($target_dir)){
                    mkdir($target_dir);
                }
                $target_dir = 'storage/notice/' . $notice1->id . '/';
                // Create directory
                if(!file_exists($target_dir)){
                    mkdir($target_dir);
                }
    
                $target_file = $target_dir . '/' . basename($_FILES["fileToUpload"]["name"]); //$target_file specifies the path of the file to be uploaded
                if(isset($request['fileToUpload']) && file_exists($target_file)){
                    array_map('unlink', glob($target_file)); // ako postoji file - briše ga
                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 
                } 
    
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
                // Check if image file is a actual image or fake image

                // Check if file already exists
                if (file_exists($target_file)) {
                    return redirect()->back()->with('error',  __('ctrl.file_exists'));  
                    $uploadOk = 0;
                }
                
                /* Check file size*/
                if ($_FILES["fileToUpload"]["size"] > 5000000) {
                    $uploadOk = 0;
                    return redirect()->back()->with('error',  __('ctrl.file_toolarge'));  
                }
                /* Allow certain file formats */
                if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                    return redirect()->back()->with('error', __('ctrl.allow') . ' jpg, png, pdf, gif');  
                }
                if($imageFileType == "exe" || $imageFileType == "bin") {
                    $uploadOk = 0;
                    return redirect()->back()->with('error', __('ctrl.not_allow'));  
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    return redirect()->back()->with('error', __('ctrl.not_uploaded')); 
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

                        if($request['schedule_set'] == 0 || strtotime($now) >= strtotime($notice1->schedule_date) ) {
                            $all_dep_employee = array();
                            foreach($request['to_department'] as $department_id) {
                                $departments_employees = Department::allDepartmentsEmployeesEmail( $department_id);
                                $all_dep_employee = array_merge($all_dep_employee, $departments_employees);
                            }
                            Log::info($all_dep_employee);
                            foreach (array_unique($all_dep_employee) as $mail) {
                                Mail::to($mail)->send(new NoticeMail($notice1));
                            }   
                            /*   try {               
                            } catch (\Throwable $th) {
                                $message = session()->flash('success',  __('emailing.not_send'));
                                return redirect()->back()->withFlashMessage($message);
                            } */
                        }
                    
                        return redirect()->back()->with('success', __('ctrl.notice_saved'));
                    //  return redirect()->back()->with('success',"The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
                    } else {
                        return redirect()->back()->with('error', __('ctrl.notice_error')); 
                    }
                }
            }
            /* ************************* SEND MAIL *********************************** */
     
            if($request['schedule_set'] == 0 || strtotime($now) >= strtotime($notice1->schedule_date) ) {
                $all_dep_employee = array();
                foreach($request['to_department'] as $department_id) {
                    $departments_employees = Department::allDepartmentsEmployeesEmail( $department_id);
                    $all_dep_employee = array_merge($all_dep_employee, $departments_employees);
                }
                Log::info($all_dep_employee);
                foreach (array_unique($all_dep_employee) as $mail) {
                    Mail::to($mail)->send(new NoticeMail($notice1));
                }   
                /*   try {               
                } catch (\Throwable $th) {
                    $message = session()->flash('success',  __('emailing.not_send'));
                    return redirect()->back()->withFlashMessage($message);
                } */
            }
 
            $message = session()->flash('success', __('ctrl.notice_saved'));
            return redirect()->back()->withFlashMessage($message);

           /* ********************************************************************* */

        } else {
            $message = session()->flash('error', __('ctrl.path_not_allow') . ', ' .  __('ctrl.notice_only_employee'));
            return redirect()->back()->withFlashMessage($message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notice = Notice::find($id);
        $notice->delete();
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function noticeboard(Request $request)
    {
        $today = date('Y-m-d'); // 2019-10-16
        $time = date('H:i:s'); // 14:49:05
        
        $departments = Department::get();
        $user_department = array();
        $permission_dep = array();
        
        if(isset($request['sort'])) { 
            $sort = $request['sort'];
        } else {
            $sort = 'DESC';	
        }

        if(isset($request['date'])) {
            $year = $request['date'];
        } else {
            $year = date('Y');
        }

        if(Sentinel::inRole('administrator')) {
            $notices = Notice::whereYear('created_at', $year)->orderBy('created_at', $sort)->get();        
        } else {
            $notices = NoticeController::getNotice($sort, $year);
        }

        $empl = Sentinel::getUser()->employee;
        
		if($empl) {
            array_push($user_department, $empl->work->department->id);  // odjel korisnika
            array_push($user_department, $departments->where('level1',0)->first()->id);  //svi

			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		}
 
        $years = array();
        $years = Notice::get()->unique(function($item){
            return $item['created_at']->year;
        })->map(function($item){
            return $item['created_at']->year;
        })->sort()->toArray();

        rsort($years);
      
        return view('Centaur::noticeboard', ['notices' => $notices,'user' => $empl,'sort' => $sort,'departments' => $departments, 'permission_dep' => $permission_dep, 'user_department' => $user_department, 'today' => $today, 'time' => $time, 'years' => $years]);
    }

    public function schedule ()
    {
        return view('Centaur::notices.schedule');
    }

    public static function getNotice ( $sort, $year ) {
        $today = date('Y-m-d'); // 2019-10-16
        $time = date('H:i:s'); // 14:49:05
       
       /*  $notices1 = Notice::whereDate('schedule_date','=', $today )->whereTime('schedule_date','<',  $time )->orderBy('schedule_date',$sort)->get();
        $notices2 = Notice::whereDate('schedule_date','<', $today )->whereYear('schedule_date', $year)->orderBy('schedule_date',$sort)->get();
        $notices3 = Notice::where('schedule_date', null)->whereYear('created_at', $year)->orderBy('schedule_date',$sort)->get();
        $notices = $notices1->merge( $notices2, $notices3 ); */
        $notices = Notice::whereYear('created_at', $year)->orderBy('created_at', $sort)->get();

        /* FILTER !!! ako ima date schedule!!!!  */
        return $notices;
    }

    public static function getNoticeDashboard ( ) {
        $user_department = DashboardController::getUserDepartment();
       
        $notices = NoticeController::getNotice('DESC', date('Y'));
        
        $notices_user = collect();

        if(! Sentinel::inRole('administrator') || ! Sentinel::getUser()->hasAccess(['notices.create'])) {
            foreach ($notices as $notice) {
                $notice_dep = explode(',', $notice->to_department);
                if(in_array(10, $notice_dep) ) {  // za SVI
                    $notices_user->push( $notice );
                } else {
                    if( array_intersect($user_department, $notice_dep ) ) {
                        $notices_user->push( $notice );
                    }
                }
            }
            $notices_user->sortByDesc('schedule_date');
           
            $filtered = $notices_user->filter(function ($notice, $key) {
                return strtotime($notice->schedule_date) < strtotime(date('Y-m-d H:i'));
            });
            $notices = $filtered;
        }
        return $notices;
    }


    public function test_mail_open (Request $request)
    {
        return view('Centaur::notices.test_mail',['notice_id' => $request['id']]);
    }

    public function sendTestEmail(Request $request) 
	{
        $send_to = $request['recipient'];
        
        $notice = Notice::find($request['notice_id']);

        if( $send_to != null ) {
            try {
                Mail::to($send_to)->send(new NoticeMail($notice)); 
            } catch (\Throwable $th) {
                $message = session()->flash('error', __('emailing.not_send'));
		        return redirect()->back()->withFlashMessage($message);
            }
        }

		$message = session()->flash('success', __('emailing.email_send'));
		
		return redirect()->back()->withFlashMessage($message);
	}

}
