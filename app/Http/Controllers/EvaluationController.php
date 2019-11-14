<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationCategory;
use App\Models\EvaluationRating;
use App\Models\EvaluationEmployee;
//use App\Models\EvaluationTarget;
use App\Models\Questionnaire;
use App\Models\Employee;
use Sentinel;
use DateTime;

class EvaluationController extends Controller
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
    public function index()
    {
        $evaluations = Evaluation::get();

		$empl = Sentinel::getUser()->employee;
		$permission_dep = array();
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		} 
		
		$employees = Employee::join('users','employees.user_id', '=', 'users.id')->select('employees.*','users.first_name','users.last_name')->orderBy('users.last_name','ASC')->where('checkout',null)->get();
		$questionnaires = Questionnaire::get();
		$evaluationCategories = EvaluationCategory::get();
		$mjesec_godina = EvaluationEmployee::select('mm_yy')->distinct()->get();
		$mjeseci = array();
		foreach($mjesec_godina as $mjesec) {
			array_push($mjeseci, $mjesec->mm_yy);
		}

		$evaluationEmployees  = EvaluationEmployee::get();
	
		return view('Centaur::evaluations.index',['evaluationEmployees'=>$evaluationEmployees,'permission_dep'=>$permission_dep ,'evaluations'=>$evaluations,'mjeseci'=>$mjeseci,'employees'=>$employees,'questionnaires'=>$questionnaires,'evaluationCategories'=>$evaluationCategories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$datum = new DateTime('now');
		$mjesec_godina = date_format($datum,'Y-m');
		$emp = Employee::where('id', $request['employee_id'])->first(); // djelatnik koji ocjenjuje

		if(!$request['rating']) {
				$message = session()->flash('error', __('basic.survey_without_rating'));
				return redirect()->back()->withFlashMessage($message);
		}
			
			$input = $request->except(['_token']);
		
			if($input['tip_ankete'] == 'podgrupa') {

				if(count($input['question_id']) != count($input['rating'])) {
					$message = session()->flash('error', __('basic.survey_not_complete'));
					return redirect()->back()->withFlashMessage($message);
				} else {
					foreach($input['question_id'] as $key => $question){
						$evaluationQuestion = EvaluationQuestion::where('id',$question)->first();
						$category = EvaluationCategory::where('id',$evaluationQuestion->category_id)->first();
						
						foreach($input['rating'] as $key2 => $value2){
							if($key2 === $key){
								$rating = $value2;
								$data = array(
									'employee_id'  	=> $input['ev_employee_id'],
									'date'     	=> $input['datum'],
									'category_id'	 	=> $category->id,
									'questionnaire_id' => $input['questionnaire_id'],
									'question_id'	=> $question,
									'koef'			=> $category->coefficient,
									'rating'	 	=> $rating
								);
								
								if($input['ev_employee_id'] === $input['employee_id'] || $emp->work['naziv'] == 'Direktor poduzeća'){
									$data['user_id'] = $input['employee_id'];
								}

								$evaluation = new Evaluation();
								$evaluation->saveEvaluation($data);
							}
						}
					}
				}
			} elseif ($input['tip_ankete'] == 'grupa' ) {
				
				if(count($input['group_id']) != count($input['rating'])) {
					$message = session()->flash('error',  __('basic.survey_not_complete'));
					return redirect()->back()->withFlashMessage($message);
				} else {
					foreach($input['group_id'] as $key => $question){
						$evaluationQuestions = EvaluationQuestion::where('category_id',$question)->get();
						$category = EvaluationCategory::where('id',$question)->first();
						
						foreach($input['rating'] as $key2 => $value2){
							if($key2 === $key){
								$rating = $value2;
								
								foreach($evaluationQuestions as $evaluationQuestion) {
									$data = array(
										'employee_id'  	=> $input['ev_employee_id'],
										'date'     	=> $input['datum'],
										'category_id'	 	=> $question,
										'questionnaire_id' => $input['questionnaire_id'],
										'question_id'	=> $evaluationQuestion->id,
										'koef'			=> $category->coefficient,
										'rating'	 	=> $rating
									);
									if($input['ev_employee_id'] === $input['employee_id']){  // || $emp->work['naziv'] == 'Direktor poduzeća'
										$data['user_id'] = $input['employee_id'];
									}
									$evaluation = new Evaluation();
									$evaluation->saveEvaluation($data);
								}
							} 
						}
					}
				}
			}
			
			$data2 = array(
				'employee_id'  	 	=> $input['employee_id'],
				'ev_employee_id'  	=> $input['ev_employee_id'],
				'mm_yy'  	=> $mjesec_godina,
				'questionnaire_id'  => $input['questionnaire_id'],
				'status'  			=> "OK"
			);
			
			$evaluationEmployee = new EvaluationEmployee();
			$evaluationEmployee->saveEvaluationEmployee($data2);

			$message = session()->flash('success', 'Anketa je snimljena');
			
			$user = Sentinel::getUser();
			
			$employee = Employee::join('users','employees.user_id', '=', 'users.id')->select('employees.*','users.first_name','users.last_name')->where('first_name', $user->first_name)->where('last_name', $user->last_name)->first();
			
			$employees = Employee::get();
			$questionnaire = Questionnaire::find($input['questionnaire_id']);
			$evaluationCategory = EvaluationCategory::where('questionnaire_id', $questionnaire->id)->get();
			$evaluatingQuestion = EvaluationQuestion::get();
			$evaluatingRatings = EvaluationRating::get();
			$evaluatingEmployees = EvaluationEmployee::where('employee_id', $employee->id)->where('status', null)->get();
			
			$message = session()->flash('success', __('questionnaire.completed'));
			
			return redirect()->route('dashboard',$questionnaire->id)->with('modal','true')->with('evaluation','true')->withFlashMessage($message);
    
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $employee = Employee::where('id',$id)->first();
		
		$evaluations = Evaluation::where('employee_id',$id)->where('date', 'LIKE' ,$request['mjesec_godina'].'%')->where('questionnaire_id',$request['questionnaire_id'])->get();
		$evaluationEmployees = EvaluationEmployee::where('ev_employee_id',$employee->id)->get();
		
		$evaluation_D = $evaluationEmployees->where('employee_id',58)->where('ev_employee_id',$employee->employee_id)->first(); // Direktor
		
		$questionnaire = Questionnaire::where('id', $request['questionnaire_id'])->first();
		$evaluationCategories = EvaluationCategory::where('questionnaire_id', $request['questionnaire_id'])->get();
		$evaluatingQuestions = EvaluationQuestion::get();
		$mjesec_godina = $request['mjesec_godina'];
		$ratings = EvaluationRating::get();
	//	$targets = EvaluationTarget::where('questionnaire_id',$questionnaire->id)->where('employee_id',$id)->where('mjesec_godina',$mjesec_godina)->get();
		
		return view('Centaur::evaluations.show', ['employee' => $employee, 'evaluation_D' => $evaluation_D, 'evaluations' => $evaluations,'questionnaire' => $questionnaire, 'evaluationCategories' => $evaluationCategories,  'evaluatingQuestions' => $evaluatingQuestions, 'mjesec_godina' =>$request['mjesec_godina'], 'evaluationEmployees' => $evaluationEmployees, 'questionnaire_id' => $request['questionnaire_id'], '$mjesec_godina' => $mjesec_godina, 'ratings' => $ratings]);
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
}
