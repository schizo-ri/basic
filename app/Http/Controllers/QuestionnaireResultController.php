<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\QuestionnaireController;
use App\Models\QuestionnaireResult;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationCategory;
use App\Models\EvaluationAnswer;
use App\Models\Questionnaire;
use App\Models\Employee;
use Sentinel;
use DateTime;

class QuestionnaireResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $questionnaire = Questionnaire::find($request['id']);
        
		$evaluationCategories = EvaluationCategory::where('questionnaire_id', $questionnaire->id)->get();
		
		$evaluationQuestions = collect();
		foreach($evaluationCategories as $category ) {
			$evaluationQuestions = $evaluationQuestions->merge(EvaluationQuestion::where('category_id', $category->id)->get());
		}
		$evaluationAnswers = collect();
		foreach($evaluationQuestions as $question ) {
            $evaluationAnswers = $evaluationAnswers->merge(EvaluationAnswer::where('question_id', $question->id)->get());
            
        }

        $results = QuestionnaireResult::where('questionnaire_id',$questionnaire->id)->get();
        foreach($evaluationCategories as $category) {
            foreach($evaluationQuestions->where('category_id', $category->id) as $question) {
                foreach($evaluationAnswers->where('question_id', $question->id) as $answer) {
                    if( $answer->question_id == $question->id) {
                        if($question->type != 'IN') {
                            $results_answers = $results->where('question_id', $question->id )->where('answer_id', $answer->id);
                            $countResults = count($results_answers);
                            $countQuestionnaire = count($results->unique('employee_id'));
                            $answer->setAttribute('count',round($countResults / $countQuestionnaire *100,2));
                        } 
                    }
                }
            }
        }
        $evaluationAnswers = $evaluationAnswers->sortByDesc('count');
       
        return view('Centaur::questionnaire_results.index',['results' => $results,'questionnaire' => $questionnaire,'evaluationCategories' => $evaluationCategories,'evaluationQuestions' => $evaluationQuestions,'evaluationAnswers' => $evaluationAnswers]);
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
        $employee = Sentinel::getUser()->employee;
       
        foreach($request['rating'] as $key1 => $answer) {
            foreach($request['question_id'] as $key => $question_id) {
                $question = EvaluationQuestion::find($question_id);
                $key2 = $key1;
                if($key == $key2) {
                    $data = array(
                        'employee_id'  	=> $employee->id,
                        'question_id'	=> $question->id,
                        'questionnaire_id'	=> $request['questionnaire_id']
                    );
                    if($question->type == 'IN') {
                        $data += ['answer' => $answer];
                    } elseif ($question->type == 'RB') {
                        $data += ['answer_id' => $answer];
                    } elseif ($question->type == 'CB') {
                        if(strpos($key1, '_')) {
                            $key2 = strstr($key1, '_', true);
                            $answer_id =str_replace('_','', strstr($key1, '_'));
                        } 
                        $data += ['answer_id' => $answer_id];
                    }
                    $results = new QuestionnaireResult();
                    $results->saveResults($data);
                }
            } 
       }

       $message = session()->flash('success', __('questionnaire.completed'));
			
		return redirect()->route('dashboard')->with('modal','true')->with('evaluation','true')->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
    
        $empl_results = QuestionnaireResult::where('questionnaire_id',$questionnaire->id)->where('employee_id',$empl->id)->get();
       
		return view('Centaur::questionnaire_results.show', ['questionnaire'=> $questionnaire, 'evaluationCategories' => $evaluationCategories, 'evaluationQuestions' => $evaluationQuestions, 'evaluationAnswers'=>$evaluationAnswers, 'permission_dep'=>$permission_dep, 'empl_results'=>$empl_results  ]); 
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
