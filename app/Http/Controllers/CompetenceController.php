<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Competence;
use App\Models\CompetenceDepartment;
use App\Models\CompetenceRating;
use App\Models\Department;
use App\Models\Work;
use App\Models\Employee;
use Sentinel;

class CompetenceController extends Controller
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
        if (Sentinel::inRole('administrator') || Sentinel::inRole('moderator')) {
            $competences = Competence::get();
        } else {
            $user = Sentinel::getUser();
            $employee = $user->employee;
            $work_id = $employee->work ? $employee->work->id : null;
            $departments_id = $employee->employeesDepartment();
            $competences = collect();

            if( $departments_id && ! empty( $departments_id) ) {
                $competences = $competences->merge(Competence::join('competence_departments','competence_departments.competence_id','competences.id')->select('competences.*','competence_departments.department_id','competence_departments.work_id')->where('competences.status', 1)->whereIn('competence_departments.department_id', $departments_id)->where('status',1)->get());
            }
            if( $work_id ) {
                $competences = $competences->merge(Competence::join('competence_departments','competence_departments.competence_id','competences.id')->select('competences.*','competence_departments.department_id','competence_departments.work_id')->where('competences.status', 1)->where('competence_departments.work_id',  $work_id )->where('status',1)->get());
            }
            $competences = $competences->merge(Competence::where('employee_id', $employee->id)->get());
           
        }
       
        return view('Centaur::competences.index', ['competences' => $competences]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::orderBy('level1','ASC')->orderBy('name','ASC')->get();
        $works = Work::orderBy('name','ASC')->get();
        $employees = Employee::employees_lastNameASCStatus(1);

        return view('Centaur::competences.create', ['departments' => $departments,'works' => $works,'employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array(
			'name'         => $request['name'],
			'nameUKR'      => $request['nameUKR'],
			'description'  => $request['description'],
			'descriptionUKR'  => $request['descriptionUKR'],
			'status'       => $request['status'],
			'employee_id'  => $request['employee_id']
		);
		
		$competence = new Competence();
        $competence->saveCompetence($data);
      
        // Zapis odjela i radnih mjesta za kompetenciju
            if( isset( $request['department_id'] ) && $request['department_id'] ) {
                foreach ($request['department_id']  as $department_id ) {
                    if( $department_id && $department_id != '' ) {
                        $data_dep = array(
                            'competence_id' => $competence->id,
                            'department_id' => $department_id,
                        );
                        
                        $department = new CompetenceDepartment();
                        $department->saveCompetenceDepartment($data_dep);
                    }
                }
            }
            if( isset( $request['work_id'] ) && $request['work_id'] ) {
                foreach ($request['work_id']  as $work_id ) {
                    if( $work_id && $work_id != '' ) {
                        $data = array(
                            'competence_id' => $competence->id,
                            'work_id' => $work_id,
                        );
                        
                        $department = new CompetenceDepartment();
                        $department->saveCompetenceDepartment($data);
                    }
                }
            }
        // Kraj - Zapis odjela i radnih mjesta za kompetenciju
        
        // Zapis ocjena
            foreach ($request['rating'] as $rating_key => $rating) {
                if( $rating ) {
                    $data_rating = array(
                        'competence_id' => $competence->id,
                        'rating'        => $rating,
                        'description'   => $request['r_description'][ $rating_key ],
                        'descriptionUKR'=> $request['r_descriptionUKR'][ $rating_key ]
                    );
                    
                    $rating = new CompetenceRating();
                    $rating->saveCompetenceRating($data_rating);
                }
            }
        // Kraj - Zapis ocjena

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
        $competence = Competence::with('hasGroups')->with('hasDepartments')->with('hasEvaluations')->find($id);
        $employees = collect();
        $user = Sentinel::getUser();
        $this_employee = $user->employee;

        if( count($competence->hasDepartments) > 0 ) {
            foreach ($competence->hasDepartments as $competence_dep) {
                if( $competence_dep->department->level1 == 0 ) {
                    $employees = Employee::employees_lastNameASCStatus(1);
                } else {
                    if( $competence_dep->department && count($competence_dep->department->hasEmployeeDepartment ) > 0) {
                        foreach ($competence_dep->department->hasEmployeeDepartment as $employeeDepartment) {
                            if($employeeDepartment->employee && ! $employeeDepartment->employee->checkout ) {
                                $employees->push($employeeDepartment->employee);
                            }
                        }
                    }
                    if( $competence_dep->work && count($competence_dep->work->workers ) > 0) {
                        foreach ($competence_dep->work->workers as $employee) {
                            if( $employee  && ! $employee->checkout ) {
                                $employees->push($employee);
                            }
                        }
                    }
                }
            }
        }

        $evaluations = $competence->hasEvaluations->where('employee_id', $this_employee->id);

        return view('Centaur::competences.show', ['competence' => $competence, 'employees' => $employees, 'evaluations' => $evaluations, 'this_employee' => $this_employee]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $competence = Competence::find($id);
        $departments = Department::orderBy('level1','ASC')->orderBy('name','ASC')->get();
        $works = Work::orderBy('name','ASC')->get();
        $employees = Employee::employees_lastNameASCStatus(1);

        return view('Centaur::competences.edit', ['competence' => $competence,'departments' => $departments,'works' => $works,'employees' => $employees]);
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
        $competence = Competence::find($id);

        $data = array(
			'name'         => $request['name'],
			'nameUKR'      => $request['nameUKR'],
			'description'  => $request['description'],
			'descriptionUKR'  => $request['descriptionUKR'],
			'status'       => $request['status'],
			'employee_id'  => $request['employee_id']
		);
		
        $competence->updateCompetence($data);
      
        // Zapis odjela i radnih mjesta za kompetenciju
            $competence_departments = $competence->hasDepartments;

            if( isset( $request['department_id'] ) && $request['department_id'] ) {
                foreach ($request['department_id']  as $department_id ) {
                    if( $department_id && $department_id != '' ) {
                        $department = $competence_departments->where('department_id', $department_id)->first();

                        $data_dep = array(
                            'competence_id' => $competence->id,
                            'department_id' => $department_id,
                        );
                        
                        if( $department ) {
                            $department->updateCompetenceDepartment($data_dep);
                        } else {
                            $department = new CompetenceDepartment();
                            $department->saveCompetenceDepartment($data_dep);
                        }
                    }
                    foreach ($competence_departments->where('department_id','<>',null) as $competence_department) {
                        if( ! in_array( $competence_department->department_id , $request['department_id'] )) {
                            $competence_department->delete();
                        }
                    }
                }
            } else {
                if( $competence_departments && count( $competence_departments) > 0) {
                    foreach ($competence_departments->where('department_id','<>',null) as $competence_department) {
                        $competence_department->delete();
                    }
                }
            }
            if( isset( $request['work_id'] ) && $request['work_id'] ) {
                foreach ($request['work_id']  as $work_id ) {
                    if( $work_id && $work_id != '' ) {
                        $work = $competence_departments->where('work_id', $work_id)->first();

                        $data_dep = array(
                            'competence_id' => $competence->id,
                            'work_id' => $work_id,
                        );
                        
                        if( $work ) {
                            $work->updateCompetenceDepartment($data_dep);
                        } else {
                            $work = new CompetenceDepartment();
                            $work->saveCompetenceDepartment($data_dep);
                        }
                    }
                    foreach ($competence_departments->where('work_id','<>', null) as $competence_department) {
                        if( ! in_array( $competence_department->work_id , $request['work_id'] )) {
                            $competence_department->delete();
                        }
                    }
                }
            } else {
                if( $competence_departments && count( $competence_departments) > 0) {
                    foreach ($competence_departments->where('work_id','<>', null) as $competence_department) {
                        $competence_department->delete();
                    }
                }
            }
        // Kraj - Zapis odjela i radnih mjesta za kompetenciju

        // Zapis ocjena
            $competence_ratings = $competence->hasRatings;
         
            foreach ($request['r_id'] as $id_key => $id) {
                $comp_rating = $competence_ratings->where('id', $id )->first();
                $rating = $request['rating'][ $id_key ];

                $data_rating = array(
                    'competence_id' => $competence->id,
                    'rating'        => $rating,
                    'description'   => $request['r_description'][ $id_key ],
                    'descriptionUKR'   => $request['r_descriptionUKR'][ $id_key ]
                );
                
                if( $rating && $comp_rating) {                    
                    $comp_rating->updateCompetenceRating($data_rating);
                } else {
                    $comp_rating->delete();
                }
            }
            foreach ($request['rating'] as $id_rating => $rating_new) {
                if( ! isset($request['r_id'][$id_rating]) ) {
                    $data_rating = array(
                        'competence_id' => $competence->id,
                        'rating'        => $rating_new,
                        'description'   => $request['r_description'][ $id_rating ],
                        'descriptionUKR'   => $request['r_descriptionUKR'][ $id_key ]
                    );
                    $comp_rating = new CompetenceRating();
                    $comp_rating->saveCompetenceRating($data_rating);
                }
            } 
        // Kraj - Zapis ocjena

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
        $competence = Competence::find($id);

        if(  $competence ) {
            if( $competence->hasDepartments && count($competence->hasDepartments) > 0 ) {
                foreach ($competence->hasDepartments as $departments) {
                    $departments->delete();
                }
            }
            if( $competence->hasGroups && count($competence->hasGroups) > 0 ) {
                foreach ($competence->hasGroups as $group) {
                    $questions = $group->hasQuestions;
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
                    $group->delete();
                }
            }
            if( $competence->hasRatings && count($competence->hasRatings) > 0 ) {
                foreach ($competence->hasRatings as $rating) {
                    $rating->delete();
                }
            }
            $competence->delete();
        }
       
        session()->flash('success',  __('ctrl.data_delete'));
		return redirect()->back();
    }
}
