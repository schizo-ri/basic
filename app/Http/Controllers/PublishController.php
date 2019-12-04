<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\ProjectEmployee;
use App\Models\Publish;
use App\Models\Project;
use App\Models\PublishProject;
use DateTime;
use DatePeriod;
use DateInterval;

class PublishController extends Controller
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
        $date = new DateTime();
        $date->modify('-14 days');

        $projects = Project::get();
        $publishProjects = PublishProject::get();
        $project_employees_all = ProjectEmployee::get();

        foreach ($publishProjects as $publishProject) {
           $publishProject->delete();
          
        }

        foreach ($projects as $project) {           
            $project_employees_last = ProjectEmployee::where('project_id', $project->id)->orderBy('date','DESC')->first();
        
            // snima projekt
            $data = array(
                'name' => $project->name,
                'project_id'  => $project->id,
                'project_no'  => $project->project_no,
                'start_date'  => $project->start_date,
                'duration'  => $project->duration,
                'day_hours'  => $project->day_hours,
                'saturday'  => $project->saturday,
                'categories'  =>  $project->categories,
            );
            if ($project->end_date != null) {
                $data += ['end_date'  => $project->end_date];
            } else {
                $data += ['end_date'  => null];
            }

            $publishProject = new PublishProject();
            $publishProject->savePublishProject($data);

            if($project_employees_last) {
                if($project_employees_last->date >= date_format($date,'Y-m-d')) { 
                    //snima djelatnike na projektu                
                    
                    $publishes = Publish::where('project_id', $project->id)->get();
                    foreach ($publishes as $publish) {
                        $publish->delete();
                    }    
                    $project_employees = ProjectEmployee::where('project_id', $project->id)->orderBy('date','DESC')->get();
                    
                    foreach ($project_employees as $project_employee) {
                        $data = array(
                            'project_id'    => $project_employee->project_id,
                            'employee_id'   => $project_employee->employee_id,
                            'date'          => $project_employee->date 
                        );
                        
                        $publish = new Publish();
                        $publish->savePublish($data);
                    }                        
                }
            }
        }
       
        session()->flash('success', "Podaci su objavljeni");
        return redirect()->back();        
    }

    public function saveImg(Request $request) {
        $date = date('Ymd_Hi');
        $title = 'Raspored_' .  $date;
        $path = 'schedules/';
       
        $imagedata = base64_decode($request['imgCanvas']);
        $filename = md5(uniqid(rand(), true));
        //path where you want to upload image
        $file = $_SERVER['DOCUMENT_ROOT'] . '/schedules/Raspored.png';
   //     $imageurl  = 'http://example.com/uploads/'.$filename.'.png';
        file_put_contents($file,$imagedata);

        $date = new DateTime();
        $date->modify('-14 days');

        $projects = Project::get();
        $publishProjects = PublishProject::get();
        $project_employees_all = ProjectEmployee::get();
        $publishes = Publish::get();
        foreach ($publishProjects as $publishProject) {           
                $publishProject->delete();
        }

        foreach ($publishes as $publish) {
            if($publish->date >= date_format($date,'Y-m-d')) { 
                $publish->delete();
            }
         }
        foreach ($projects as $project) {           
            $project_employees_last = ProjectEmployee::where('project_id', $project->id)->orderBy('date','DESC')->first();            
          
            // snima projekt
            $data = array(
                'name' => $project->name,
                'project_id'  => $project->id,
                'project_no'  => $project->project_no,
                'start_date'  => $project->start_date,
                'duration'  => $project->duration,
                'day_hours'  => $project->day_hours,
                'saturday'  => $project->saturday,
                'categories'  =>  $project->categories,
            );
            if ($project->end_date != null) {
                $data += ['end_date'  => $project->end_date];
            } else {
                $data += ['end_date'  => null];
            }

            $publishProject = new PublishProject();
            $publishProject->savePublishProject($data);

            if($project_employees_last) {
                if($project_employees_last->date >= date_format($date,'Y-m-d')) { 
                    //snima djelatnike na projektu
                    
                    $project_employees = ProjectEmployee::where('project_id', $project->id)->orderBy('date','DESC')->get();
                    
                    foreach ($project_employees as $project_employee) {
                        $data = array(
                            'project_id'    => $project_employee->project_id,
                            'employee_id'   => $project_employee->employee_id,
                            'date'          => $project_employee->date 
                        );
                        
                        $publish = new Publish();
                        $publish->savePublish($data);
                    }                        
                }
            }
        }

        session()->flash('success', "Podaci su objavljeni");
        return redirect()->back();
    }
}
