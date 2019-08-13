<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\QuestionnaireRequest;
use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\EvaluationCategory;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationRating;
use App\Models\EvaluationEmployee;
use App\Models\Employee;
use DateTime;
use Sentinel;
use App\Mail\QuestionnaireSend;
use Illuminate\Support\Facades\Mail;
use App\Models\Emailing;
use App\Models\Department;

class QuestionnaireController extends Controller
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
		$questionnaires = Questionnaire::get();
		$empl = Sentinel::getUser()->employee;
		$permission_dep = array();
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		} 
		
		return view('Centaur::questionnaires.index', ['questionnaires' => $questionnaires, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::questionnaires.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionnaireRequest $request)
    {
		$data = array(
			'name'  		=> $request['name'],
			'description'	=> $request['description'],
			'status'  	 	=> $request['status']
		);
			
		$questionnaire = new Questionnaire();
		$questionnaire->saveQuestionnaire($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('questionnaires.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
		$user = Sentinel::getUser();
		$danas = new DateTime('now');
		$mjesec_godina = date_format($danas,'Y-m');

		$questionnaire = Questionnaire::find($id);
		 
		$evaluationCategories = EvaluationCategory::where('questionnaire_id', $questionnaire->id)->get();
		$evaluationQuestion = EvaluationQuestion::get();
		$evaluationRatings = EvaluationRating::get();
		$employees = Employee::where('checkout',null)->get(); //svi djelatnici
		
		$employee = Employee::where('user_id', $user->id)->first();
		if($employee) {
			$evaluationEmployees = EvaluationEmployee::where('employee_id', $employee->id)->where('mm_yy',$mjesec_godina)->get();  //svi djelatnici koje je korisnik ocjenio
			
			return view('Centaur::questionnaires.show',['employee'=>$employee,'employees'=>$employees,'questionnaire'=>$questionnaire,'evaluationCategories'=>$evaluationCategories,'evaluationEmployees'=>$evaluationEmployees,'evaluationQuestion'=>$evaluationQuestion,'evaluationRatings'=>$evaluationRatings]); 
		} else {
			$message = session()->flash('success', 'Putanja nije dozvoljena!');
		
			return redirect()->back()->withFlashMessage($message);
		}
		
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $questionnaire = Questionnaire::find($id);
		
		return view('Centaur::questionnaires.edit',['questionnaire'=>$questionnaire]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionnaireRequest $request, $id)
    {
        $questionnaire = Questionnaire::find($id);
		
		$data = array(
			'name'  		=> $request['name'],
			'description'	=> $request['description'],
			'status'  	 	=> $request['status']
		);
			
		$questionnaire->updateQuestionnaire($data);
		
		session()->flash('success', "Podaci su ispravljeni");
		
        return redirect()->route('questionnaires.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $questionnaire = Questionnaire::find($id);
		$questionnaire->delete();
		
		$message = session()->flash('success', 'Anketa je obrisana.');
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	public function sendEmail(Request $request) 
	{
		/* mail obavijest o novoj poruci */
		$emailings = Emailing::get();
		$send_to = array();
		$departments = Department::get();
		$employees = Employee::get();
		$questionnaire = Questionnaire::find($request['id']);
		
		if(isset($emailings)) {
			foreach($emailings as $emailing) {
				if($emailing->table['name'] == 'questionnaires' && $emailing->method == 'create') {
					
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
		
		if($send_to) {
			foreach($send_to as $send_to_mail) {
				if( $send_to_mail != null & $send_to_mail != '' ) {
					Mail::to($send_to_mail)->send(new QuestionnaireSend($questionnaire)); // mailovi upisani u mailing 
				}
			}
		}
		$message = session()->flash('success', __('emailing.email_send'));
		
		return redirect()->back()->withFlashMessage($message);
		
	}
}
