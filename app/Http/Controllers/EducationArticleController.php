<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\EducationArticle;
use App\Models\EducationTheme;
use App\Models\Employee;
use Sentinel;
use App\Mail\EducationArticleMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Emailing;
use App\Models\Department;

class EducationArticleController extends Controller
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
    public function index(Request $request)
    {
        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
		
		if($request->theme_id){
			$educationArticles = EducationArticle::where('theme_id',$request->theme_id)->get();
			$educationTheme = EducationTheme::where('id', $request->theme_id)->first();

			return view('Centaur::education_articles.index', ['educationArticles' => $educationArticles,'educationTheme' => $educationTheme,'permission_dep' => $permission_dep]);

		} else {
			$educationArticles = EducationArticle::get();
			
			return view('Centaur::education_articles.index', ['educationArticles' => $educationArticles, 'permission_dep' => $permission_dep]);
		}
		
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$educationThemes = EducationTheme::orderBy('name','ASC')->get();
		
		if($request->theme_id){
			return view('Centaur::education_articles.create',['educationThemes'=>$educationThemes, 'theme_id'=>$request->theme_id]);
		} else {
			return view('Centaur::education_articles.create',['educationThemes'=>$educationThemes]);
		}
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
		
		if(empty ($request['article'])) {
			$message = session()->flash('error', 'Nemoguće spremiti članak bez teksta.');
		
			return redirect()->back()->withFlashMessage($message);
		}
		
		$user = Sentinel::getUser();
		
		$employee = Employee::where('user_id',$user->id)->first();
		
		if($employee) {
			$employee_id = $employee->id;
		} else {
			$employee_id = null;
		}
		
		$data = array(
			'employee_id'   => $employee_id,
			'subject'   	=> $request['subject'],
			'theme_id'		=> $request['theme_id'],
			'article'  		=> $request['article'], 
			'status'  		=> $request['status']
		);
		
		$educationArticle = new EducationArticle();
		$educationArticle->saveEducationArticle($data);
		
		if($educationArticle->status == 'aktivan') {
			/* mail obavijest o novoj poruci */
			$emailings = Emailing::get();
			$send_to = array();
			$departments = Department::get();
			$employees = Employee::get();

			if(isset($emailings)) {
				foreach($emailings as $emailing) {
					if($emailing->table['name'] == 'education_articles' && $emailing->method == 'create') {
						if($emailing->sent_to_dep) {
							foreach(explode(",", $emailing->sent_to_dep) as $prima_dep) {
								array_push($send_to, $departments->where('id', $prima_dep)->first()->email );
							}
						}
						if($emailing->sent_to_empl) {
							foreach(explode(",", $emailing->sent_to_empl) as $prima_empl) {
								array_push($send_to, $employees->where('id', $prima_empl)->first()->email );
							}
						}
					}
				}
			}

			foreach($send_to as $send_to_mail) {
				if( $send_to_mail != null & $send_to_mail != '' )
				Mail::to($send_to_mail)->send(new EducationArticleMail($educationArticle)); // mailovi upisani u mailing 
			}
		}
		
		session()->flash('success', __('ctrl.data_save'));
		
        return redirect()->route('education_articles.index', ['theme_id' => $request['theme_id']]);

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
        $educationArticle = EducationArticle::find($id);
		$educationThemes = EducationTheme::orderBy('name','ASC')->get();
		
	    return view('Centaur::education_articles.edit', ['educationArticle' => $educationArticle, 'educationThemes' => $educationThemes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, $id)
    {
        if(empty ($request['article'])) {
			$message = session()->flash('error', 'Nemoguće spremiti članak bez teksta.');
		
			return redirect()->back()->withFlashMessage($message);
		}
		
		$educationArticle = EducationArticle::find($id);
		
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id',$user->id)->first();
		
		$data = array(
			'employee_id'   => $employee->id,
			'subject'   	=> $request['subject'],
			'theme_id'		=> $request['theme_id'],
			'article'  		=> $request['article'], 
			'status'  		=> $request['status']
		);
		
		$educationArticle->updateEducationArticle($data);
		
		session()->flash('success', __('ctrl.data_edit'));
		
       return redirect()->route('education_articles.index', ['theme_id' => $request['theme_id']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $educationArticle = EducationArticle::find($id);
		$educationArticle->delete();
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
