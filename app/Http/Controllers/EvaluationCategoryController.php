<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EvaluationCategoryRequest;
use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\EvaluationCategory;
use Sentinel;

class EvaluationCategoryController extends Controller
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
		
		if(isset($request['questionnaire_id'])) {
			$evaluationCategories = EvaluationCategory::where('questionnaire_id',$request['questionnaire_id'])->get();
			return view('Centaur::evaluation_categories.index', ['evaluationCategories' => $evaluationCategories, 'questionnaire_id' => $request['questionnaire_id'],'permission_dep' => $permission_dep]);
		} else {
			$evaluationCategories = EvaluationCategory::get();
			return view('Centaur::evaluation_categories.index', ['evaluationCategories' => $evaluationCategories,'permission_dep' => $permission_dep]);
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$questionnaires = Questionnaire::get();
		
		if(isset($request['questionnaire_id'])) {
			return view('Centaur::evaluation_categories.create', ['questionnaires' => $questionnaires, 'questionnaire_id' => $request['questionnaire_id']]);
		}
		
		return view('Centaur::evaluation_categories.create', ['questionnaires' => $questionnaires]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluationCategoryRequest $request)
    {
		$data = array(
			'name'  			=> $request['name'],
			'questionnaire_id'	=> $request['questionnaire_id'],
			'coefficient'  	 	=> str_replace(',', '.', $request['coefficient'])
		);
			
		$evaluationCategory = new EvaluationCategory();
		$evaluationCategory->saveCategory($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('evaluation_categories.index',['questionnaire_id' => $request['questionnaire_id']]);
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
        $evaluationCategory = EvaluationCategory::find($id);
		$questionnaires = Questionnaire::get();
		
		return view('Centaur::evaluation_categories.edit',['questionnaires'=>$questionnaires, 'evaluationCategory'=>$evaluationCategory]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EvaluationCategoryRequest $request, $id)
    {
        $evaluationCategory = EvaluationCategory::find($id);
		
		$data = array(
			'name'  			=> $request['name'],
			'questionnaire_id'	=> $request['questionnaire_id'],
			'coefficient'  	 	=> str_replace(',', '.', $request['coefficient'])
		);

		$evaluationCategory->updateCategory($data);
		
		session()->flash('success', "Podaci su ispravljeni");
		
        return redirect()->route('evaluation_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evaluationCategory = EvaluationCategory::find($id);
		$evaluationCategory->delete();
		
		$message = session()->flash('success', 'Kategorija je obrisana.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
