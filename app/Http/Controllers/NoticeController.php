<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NoticeyRequest;
use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeStatistic;
use App\Models\Department;
use App\Models\Employee;
use Sentinel;

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
        
        return view('Centaur::notices.create', ['departments0' => $departments0, 'departments1' => $departments1, 'departments2' => $departments2]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $to_department_id = implode(',', $request['to_department']);

        if(Sentinel::getUser()->employee) {
            $employee_id = Sentinel::getUser()->employee->id;

            $notice = $request['notice'];
            $dom = new \DomDocument();
            $dom->loadHtml(mb_convert_encoding($notice, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $images = $dom->getElementsByTagName('img');
            
            foreach($images as $k => $img){
                $data = $img->getAttribute('src');
                $dataFilename = $img->getAttribute('data-filename');
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                if(!file_exists(public_path() . "/img/notices")){
                    mkdir(public_path()."/img/notices");
                }
                $image_name= "/img/notices/" . $dataFilename;
                $path = public_path() .  $image_name;
               
                file_put_contents($path, $data);
                $img->removeAttribute('src');
                $img->setAttribute('src', $image_name);
            }
                
            $notice = $dom->saveHTML();
    
            $data1 = array(
                'employee_id'   	=> $employee_id,
                'to_department'  => $to_department_id,
                'title'  			=> $request['title'],
                'notice'  			=> $notice
            );
            
            $notice1 = new Notice();
            $notice1->saveNotice($data1);
    
            foreach($request['to_department'] as $department) {
                $department = Department::where('id', $department)->first();
                $prima = $department->email;
                
                /*	Mail::queue(
                    'email.notice',
                    ['poruka' => $notice1->subject],
                    function ($message) use ($prima , $notice1->subject) {
                        $message->to($prima)
                            ->from('info@duplico.hr', 'Duplico')
                            ->subject('Obavijest uprave');
                    }
                );*/
                
            }
            
            $message = session()->flash('success', 'Obavijest je poslana');
            return redirect()->route('notices.index')->withFlashMessage($message);
        } else {
            $message = session()->flash('error', 'Putanja nije dozvoljena, obavijest može generirat samo zaposlenik.');
            return redirect()->back()->with('modal','true')->withFlashMessage($message);
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
        $employee_id = Sentinel::getUser()->employee->id;

        if(! NoticeStatistic::where('notice_id', $notice->id)->where('employee_id',  $employee_id)->first() ) {
            $data = array(
                'employee_id'   => $employee_id,
                'notice_id'     => $notice->id,
                'status'  		=> 1
            );
            
            $statistic = new NoticeStatistic();
            $statistic->saveStatistic($data);
        }
        
        $notice_statistic = NoticeStatistic::where('notice_id', $notice->id)->get();
        $employees = Employee::where('checkout',null)->get();
        $count_statistic = count( $notice_statistic);
        $count_employees = count($employees);
        $statistic = $count_statistic /  $count_employees *100 ;

        return view('Centaur::notices.show', ['notice' => $notice, 'statistic' => $statistic]);
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
        
        $departments0 = Department::where('level1',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level1',1)->orderBy('name','ASC')->get();
        $departments2 = Department::where('level1',2)->orderBy('name','ASC')->get();

        return view('Centaur::notices.edit', ['notice' => $notice, 'departments' => $departments, 'departments0' => $departments0, 'departments1' => $departments1, 'departments2' => $departments2]);
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function noticeboard()
    {
        $notices = Notice::orderBy('created_at','DESC')->get();
        $departments = Department::get();
        $dataArr = array();

        $empl = Sentinel::getUser()->employee;
        $permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		}
        
        return view('Centaur::noticeboard', ['notices' => $notices,'user' => $empl,'departments' => $departments, 'permission_dep' => $permission_dep, 'dataArr' => $dataArr]);
    }
}
