<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EvaluationQuestionRequest;
use App\Http\Controllers\Controller;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationCategory;
use Sentinel;

class EvaluationQuestionController extends Controller
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
            if(isset($request['category_id'])) {
                $evaluationQuestions = EvaluationQuestion::where('category_id', $request['category_id'])->get();
                
                return view('Centaur::evaluation_questions.index', ['evaluationQuestions' => $evaluationQuestions, 'category_id' => $request['category_id'],'permission_dep' => $permission_dep]);
            } else {
                $evaluationQuestions = EvaluationQuestion::get();
                
                return view('Centaur::evaluation_questions.index', ['evaluationQuestions' => $evaluationQuestions,'permission_dep' => $permission_dep]);
            }
		}  else {
            session()->flash('error', __('ctrl.not_registered'));

            return redirect()->back();
        }
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = EvaluationCategory::get();
		
		if(isset($request['category_id'])) {
			return view('Centaur::evaluation_questions.create', ['categories' => $categories, 'category_id' => $request['category_id']]);
		} else {
			return view('Centaur::evaluation_questions.create', ['categories' => $categories]);
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluationQuestionRequest $request)
    {
		$data = array(
			'name_question'  	=> $request['name_question'],
			'category_id'		=> $request['category_id'],
            'description'  	 	=> $request['description'],
            'type'  	 	    => $request['type']
            
		);
			
		$evaluationQuestion = new EvaluationQuestion();
		$evaluationQuestion->saveEvaluatingQuestion($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('evaluation_questions.index',['category_id' => $request['category_id']]);
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
        $evaluationQuestion = EvaluationQuestion::find($id);
		$categories = EvaluationCategory::get();
		
		return view('Centaur::evaluation_questions.edit', ['categories' => $categories, 'evaluationQuestion' => $evaluationQuestion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EvaluationQuestionRequest $request, $id)
    {
        $evaluationQuestion = EvaluationQuestion::find($id);
		
		$data = array(
			'name_question'  	=> $request['name_question'],
			'category_id'		=> $request['category_id'],
            'description'  	 	=> $request['description'],
            'type'  	 	    => $request['type']
		);
			
		$evaluationQuestion->updateEvaluatingQuestion($data);
		
		session()->flash('success', "Podaci su ispravljeni");
		
        //return redirect()->route('evaluation_questions.index');
        return redirect()->back()->withFlashMessage($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd($id);
        $evaluationQuestion = EvaluationQuestion::find($id);
		$evaluationQuestion->delete();
		
		$message = session()->flash('success', 'Podkategorija je obrisana.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
