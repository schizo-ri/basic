<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Competence;
use App\Models\CompetenceEvaluation;
use Sentinel;

class CompetenceEvaluationController extends Controller
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
        $competence = Competence::with('hasEvaluations')->find($request['id']);
        $employees = collect();
        $user = Sentinel::getUser();
        $this_employee = $user->employee;

        if($competence) {
            if( count($competence->hasEvaluations) >0 ) {
                foreach ($competence->hasEvaluations as $evaluation) {
                    $employees->push( $evaluation->employee );
                }
            }

            return view('Centaur::competence_evaluations.index', ['competence' => $competence,'employees' => $employees->unique(),'this_employee' => $this_employee ]);
        } else {
            session()->flash('error', 'Kompentencija nije nađena');
            return redirect()->back();
        }
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
        foreach ($request['group_id'] as $group_id ) {
            $question_ids = $request['question_id'][$group_id];

            foreach ($question_ids as $question_id ) {
                $rating_id = $request['rating_id'][$group_id][$question_id];
                
                $data = array(
                    'competence_id'     => $request['id'],
                    'user_id'           => Sentinel::getUser()->employee->id,
                    'employee_id'       => $request['employee_id'],
                    'question_id'       => $question_id,
                    'rating_id'         => $rating_id,
                    'evaluation_date'   => date('Y-m-d')
                );
                $competenceEvaluation = new CompetenceEvaluation();
                $competenceEvaluation->saveCompetenceEvaluation($data);
            }
        }

        session()->flash('success',  __('ctrl.data_save'));
		return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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

    public function updateEvaluation(Request $request) 
    {
        $competenceEvaluation = CompetenceEvaluation::find($request['id']);

        $user_evaluation = CompetenceEvaluation::where('employee_id', $competenceEvaluation->employee_id )->where('question_id', $competenceEvaluation->question_id )->where('user_id', Sentinel::getUser()->employee->id)->first();

        $data = array(
            'competence_id'     => $competenceEvaluation->competence_id,
            'user_id'           => Sentinel::getUser()->employee->id,
            'employee_id'       => $competenceEvaluation->employee_id,
            'question_id'       => $competenceEvaluation->question_id,
            'evaluation_date'   => date('Y-m-d')
        );
        if (isset($request['rating_id']) ) {
            $data += [ 'rating_id' => $request['rating_id']];
        }
        if (isset($request['comment']) ) {
            $data += [ 'comment' => $request['comment']];
        }
          
        if( $user_evaluation)  {
            $user_evaluation->updateCompetenceEvaluation($data);
        } else {
            $competenceEvaluation = new CompetenceEvaluation($data);
            $competenceEvaluation->saveCompetenceEvaluation($data);
        }
        
        return "sve ok";
    }
}
