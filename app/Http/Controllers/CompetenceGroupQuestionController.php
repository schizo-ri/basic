<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompetenceGroupQuestion;
use App\Models\CompetenceQuestion;
use App\Models\Competence;

class CompetenceGroupQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( isset($request['competence_id'])) {
            $competence_id =  $request['competence_id'];
        } else {
            $competence_id = null;
        }
        
        return view('Centaur::competence_group_questions.create',[ 'competence_id' => $competence_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        if( $request['competence_id'] ) {
            $data = array(
                'competence_id'=> $request['competence_id'],
                'name'         => $request['name'],
                'nameUKR'      => $request['nameUKR'],
                'description'  => $request['description'],
                'descriptionUKR'  => $request['descriptionUKR'],
                'coefficient'  => $request['coefficient']
            );
            
            $competenceGroupQuestion = new CompetenceGroupQuestion();
            $competenceGroupQuestion->saveCompetenceGroupQuestion($data);
    
            foreach ($request['q_name'] as $key_question => $name) {
               if($name && $name != '') {
                    $data_question = array(
                        'group_id'     => $competenceGroupQuestion->id,
                        'name'         => $name,
                        'nameUKR'         => $request['q_nameUKR'][ $key_question ],
                        'description'  => $request['q_description'][ $key_question ],
                        'descriptionUKR'  => $request['q_descriptionUKR'][ $key_question ],
                        'rating'       => $request['q_rating'][ $key_question ],
                    );

                    $competenceQuestion = new CompetenceQuestion();
                    $competenceQuestion->saveCompetenceQuestion($data_question);
               }
            }

            session()->flash('success',  __('ctrl.data_save'));
            return redirect()->back();
        } else {
            session()->flash('error', 'Nastao je problem, nedostaje id');
            return redirect()->back();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)   // competence_id
    {
        $competenceGroup = CompetenceGroupQuestion::find($id);

        return view('Centaur::competence_group_questions.edit',[ 'competenceGroup' => $competenceGroup]);
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
        $competenceGroup = CompetenceGroupQuestion::find($id);

        $data = array(
            'name'         => $request['name'],
			'nameUKR'      => $request['nameUKR'],
			'description'  => $request['description'],
			'descriptionUKR'  => $request['descriptionUKR'],
            'coefficient'  => $request['coefficient']
        );
        
        $competenceGroup->updateCompetenceGroupQuestion($data);
        if( $request['q_id'] ) {
            foreach ($request['q_id']  as $key_id => $q_id) {
                $competenceQuestion = CompetenceQuestion::find($q_id);
                $name = $request['q_name'][$key_id];
    
                $data_question = array(
                    'group_id'     => $competenceGroup->id,
                    'name'         => $name,
                    'nameUKR'      => $request['q_nameUKR'][ $key_id ],
                    'description'  => $request['q_description'][ $key_id ],
                    'descriptionUKR'  => $request['q_descriptionUKR'][ $key_id ],
                    'rating'        => $request['q_rating'][ $key_id ],
                );
    
                if( $name && $competenceQuestion) {                    
                    $competenceQuestion->updateCompetenceQuestion($data_question);
                } else {
                    $competenceQuestion->delete();
                }
            }
        }
        if( $request['q_name'] ) {
            foreach ($request['q_name'] as $id_name => $name_new) {
                if( ! isset($request['q_id'][$id_name]) ) {
                    $data_name = array(
                        'group_id'     => $competenceGroup->id,
                        'name'         => $name_new,
                        'nameUKR'      => $request['q_nameUKR'][ $id_name ],
                        'description'  => $request['q_description'][ $id_name ],
                        'descriptionUKR'=> $request['q_descriptionUKR'][ $id_name ],
                        'rating'        => $request['q_rating'][ $id_name ],
                    );
                    $comp_rating = new CompetenceQuestion();
                    $comp_rating->saveCompetenceQuestion($data_name);
                }
            } 
        }

        session()->flash('success',  __('ctrl.data_edit'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $competenceGroup = CompetenceGroupQuestion::find($id);
        
        if( $competenceGroup ) {
            $questions = $competenceGroup->hasQuestions;
            if ($questions && count( $questions ) > 0)  {
                foreach ($questions as $question) {
                    $evaluations = $question->hasEvaluations;
                    if ($evaluations && count( $evaluations ) > 0)  {
                        foreach ($evaluations as $evaluation) {
                            $evaluation->delete();
                        }
                    }
                    $question->delete();
                }
            }
            
            $competenceGroup->delete();
        }
        
        session()->flash('success',  __('ctrl.data_delete'));
        return redirect()->back();
    }
}