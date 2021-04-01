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
use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use App\Models\Employee;
use App\Models\QuestionnaireResult;
use DateTime;
use Sentinel;
use App\Mail\QuestionnaireSend;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;
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
		$employees = Employee::employees_firstNameASC();
		$evaluations = Evaluation::get();
		$results = QuestionnaireResult::get();
		
		$permission_dep = DashboardController::getDepartmentPermission();
		
		return view('Centaur::questionnaires.index', ['questionnaires' => $questionnaires, 'permission_dep' => $permission_dep, 'employees' => $employees, 'results' => $results]);
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

		/* snima anketu */
		$data = array(
			'name'  		=> $request['name'],
			'status'  	 	=> $request['status']
		);
			
		$questionnaire = new Questionnaire();
		$questionnaire->saveQuestionnaire($data);

		/* snima kategorije */
		if(isset($request['name_category'])) {
			foreach( $request['name_category'] as $key => $category ) {
				$data_category = array(
					'name_category'  	=> $category,
					'questionnaire_id'	=> $questionnaire->id,
				);

				$evaluationCategory = new EvaluationCategory();
				$evaluationCategory->saveCategory($data_category);

				/* foreach( $request['coefficient'] as $key2 => $coefficient) {
					if( $key == $key2) {
						$data_category = array(
							'name_category'  	=> $category,
							'questionnaire_id'	=> $questionnaire->id,
						);
						if($request['coefficient'] != '' && $request['coefficient'] != null) {
							$data += [ 'coefficient' => str_replace(',', '.', $coefficient) ];
						}
						$evaluationCategory = new EvaluationCategory();
						$evaluationCategory->saveCategory($data_category);
					}
				} */
				if(isset($request['name_question'])) {
					/* snima pitanja */
					foreach( $request['name_question']  as $key3 => $question ) {
						foreach( $request['type']  as $key6 => $type ) {
							if( $key3 == $key6) {
								foreach( $request['category'] as $key5 => $category_input )  {
									if( $category ==  $category_input && $key5 ==  $key3 ) {
										$category_id = $evaluationCategory->id;
										
										$data_question = array(
											'name_question'  	=> $question,
											'category_id'		=> $category_id,
											'type'  	 	    => $type
										);
										
										$evaluationQuestion = new EvaluationQuestion();
										$evaluationQuestion->saveEvaluatingQuestion($data_question);

										if(isset($request['answer']) ) {
											foreach( $request['answer']  as $key7 => $answer ) {
												if($answer != null ) {
													foreach( $request['question']  as $key8 => $question_input ) {
														if(($key7 == $key8) && ( $evaluationQuestion->name_question == $question_input) ) {
															$data_answer = array(
																'question_id'  	=> $evaluationQuestion->id,
																'answer'  	 	=> $answer
															);
															$evaluationAnswer = new EvaluationAnswer();
															$evaluationAnswer->saveEvaluationAnswer($data_answer);
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		session()->flash('success', __('ctrl.data_save'));

		// return redirect()->route('evaluation_categories.create');
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
		$empl = Sentinel::getUser()->employee;

		$questionnaire = Questionnaire::find($id);
	
		$permission_dep = array();
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		} 
		
		$evaluationCategories = EvaluationCategory::where('questionnaire_id', $questionnaire->id)->get();
		
		$evaluationQuestions = collect();
		foreach($evaluationCategories as $category ) {
			$evaluationQuestions = $evaluationQuestions->merge(EvaluationQuestion::where('category_id', $category->id)->get());
		}
		$evaluationAnswers = collect();
		foreach($evaluationQuestions as $question ) {
			$evaluationAnswers = $evaluationAnswers->merge(EvaluationAnswer::where('question_id', $question->id)->get());
		}
	
		return view('Centaur::questionnaires.show', ['questionnaire'=> $questionnaire, 'evaluationCategories' => $evaluationCategories, 'evaluationQuestions' => $evaluationQuestions, 'evaluationAnswers'=>$evaluationAnswers, 'permission_dep'=>$permission_dep  ]); 

		/*
		$danas = new DateTime('now');
		$mjesec_godina = date_format($danas,'Y-m');
		$evaluationRatings = EvaluationRating::get();
		$employees = Employee::employees_firstNameASC(); //svi djelatnici
		
		$employee = Employee::where('user_id', $user->id)->first();
		if($employee) {
			$evaluationEmployees = EvaluationEmployee::where('employee_id', $employee->id)->where('mm_yy',$mjesec_godina)->get();  //svi djelatnici koje je korisnik ocjenio
			
			return view('Centaur::questionnaires.show',['employee'=>$employee,'employees'=>$employees,'questionnaire'=>$questionnaire,'evaluationCategories'=>$evaluationCategories,'evaluationEmployees'=>$evaluationEmployees,'evaluationQuestion'=>$evaluationQuestion,'evaluationRatings'=>$evaluationRatings]); 
		} else {
			$message = session()->flash('success',  __('ctrl.path_not_allow'));
		
			return redirect()->back()->withFlashMessage($message);
		}*/
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
		$categories = EvaluationCategory::where('questionnaire_id', $questionnaire->id )->get();
		$questions = collect();
		$answers = collect();

		foreach($categories as $category ) {
			$questions = $questions->merge(EvaluationQuestion::where('category_id', $category->id)->get());
		}

		foreach($questions as $question) {
			$answers = $answers->merge(EvaluationAnswer::where('question_id', $question->id)->get());
		}
		
		return view('Centaur::questionnaires.edit', ['questionnaire' => $questionnaire, 'categories' => $categories, 'questions' => $questions, 'answers' => $answers]);
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
		
		/* Ispravak ankete  */
		$data = array(
			'name'  		=> $request['name'],
			'status'  	 	=> $request['status']
		);
		$questionnaire->updateQuestionnaire($data);


		/* Ispravak kategorije */
		if(isset($request['name_category'])) {
			foreach( $request['name_category'] as $key1 => $category ) {  
				/* foreach( $request['coefficient'] as $key2 => $coefficient) { */
					foreach( $request['category_id'] as $key3 => $category_id) {
						if( /* $key1 == $key2 &&  */$key1 == $key3) {
							$data_category = array(
								'name_category'  	=> $category,
								'questionnaire_id'	=> $questionnaire->id
							);
							/* if($request['coefficient'] != '') {
								$data += [ 'coefficient' => str_replace(',', '.', $coefficient) ];
							} */
						
							if( $category_id) {
								$category_2 = EvaluationCategory::find($category_id);
								$category_2->updateCategory($data_category);
							} else {
								$category_2 = new EvaluationCategory();
								$category_2->saveCategory($data_category);
							}

							/* Ispravak pitanja */
							foreach( $request['category']  as $key8 => $category_name ) { 
								if( $category_name == $category_2->name_category ) {
									$category_id2 = $category_2->id;
									if(isset($request['name_question'])) {
										foreach( $request['name_question']  as $key4 => $question ) { 
											foreach( $request['type'] as $key6 => $type ) {
												foreach( $request['question_id'] as $key7 => $question_id ) {
													if(($key4 == $key6) && ($key4 == $key7) && ($key4 == $key8) ) {
														$data_question = array(
															'name_question'  	=> $question,
															'type'  	 	    => $type
														);
														if( $question_id) {
															$question_2 = EvaluationQuestion::find($question_id);
															$question_2->updateEvaluatingQuestion($data_question);
														} else {
															$data_question += ['category_id' => $category_id2];
															$question_2 = new EvaluationQuestion();
															$question_2->saveEvaluatingQuestion($data_question);
														}

														/* Ispravak odgovora */
														foreach( $request['question']  as $key9 => $question_name ) { 
															if( $question_name == $question_2->name_question ) {
																$question_id2 = $question_2->id;
																if(isset($request['answer']) ) {
																	foreach( $request['answer']  as $key10 => $answer ) {
																		foreach( $request['answer_id']  as $key11 => $answer_id ) {
																			if( $key9 == $key10 && $key9 == $key11 && $answer != null ) {
																				$data_answer = array(
																					'answer'  	 	=> $answer
																				);
																				if($answer_id != null ) {
																					$answer2 = EvaluationAnswer::find($answer_id);
																					$answer2->updateEvaluationAnswer($data_answer);
	
																				} else {
																						$data_answer += ['question_id' => $question_id2];
																						
																						$evaluationAnswer = new EvaluationAnswer();
																						$evaluationAnswer->saveEvaluationAnswer($data_answer);
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
											
										}
									}
								}
							}
						}
					}
				/* } */
			}
		}

		session()->flash('success', __('ctrl.data_edit'));
		
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
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	public function sendEmail(Request $request) 
	{
		/* mail obavijest o novoj poruci */
		//$emailings = Emailing::get();
		
		$send_to = Employee::getEmails();
		$questionnaire = Questionnaire::find($request['id']);
		
		if($send_to) {
			try {
				foreach(array_unique($send_to) as $send_to_mail) {
					if( $send_to_mail != null & $send_to_mail != '' ) {
						Mail::to($send_to_mail)->send(new QuestionnaireSend($questionnaire)); // mailovi upisani u mailing 
					}
				}
			} catch (\Throwable $th) {
				$email = 'jelena.juras@duplico.hr';
				$url = $_SERVER['REQUEST_URI'];
				Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
				
				session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
				return redirect()->back();
			}
			
		}
		$message = session()->flash('success', __('emailing.email_send'));
		
		return redirect()->back()->withFlashMessage($message);
		
	}

	public static function collectResults ($id) {
		$questionnaire = Questionnaire::find($id);
		$evaluationCategories = EvaluationCategory::where('questionnaire_id', $questionnaire->id)->get();

		$evaluationQuestions = collect();
		foreach($evaluationCategories as $category ) {
			$evaluationQuestions = $evaluationQuestions->merge(EvaluationQuestion::where('category_id', $category->id)->get());
		}
		$questionnaire_results = collect();
		foreach($evaluationQuestions as $question ) {
			$questionnaire_results = $questionnaire_results->merge( QuestionnaireResult::where('question_id', $question->id)->get());
		}

		return $questionnaire_results;
	}

	public static function progress_perc ($id) {

		$employees = Employee::employees_firstNameASC();
		
		return QuestionnaireController::progress_count( $id ) / count($employees) * 100;
	}

	public static function progress_count ($id) {
		
		return count(QuestionnaireController::collectResults( $id )->unique('employee_id'));
	}
	
}
