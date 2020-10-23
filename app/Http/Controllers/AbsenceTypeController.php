<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AbsenceTypeRequest;
use App\Http\Controllers\Controller;
use App\Models\AbsenceType;
use Sentinel;

class AbsenceTypeController extends Controller
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
        $empl = Sentinel::getUser()->employee;
		$absenceTypes = AbsenceType::get();
        $permission_dep = array();

		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
			
			return view('Centaur::absence_types.index', ['absenceTypes' => $absenceTypes, 'empl' => $empl, 'permission_dep' => $permission_dep]);
		} else {
			 return view('Centaur::absence_types.index', ['absenceTypes' => $absenceTypes, 'permission_dep' => $permission_dep]);
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('Centaur::absence_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AbsenceTypeRequest $request)
    {
        $data = array(
			'name'  	=> $request['name'],
			'temp'  	=> $request['temp'], // za privremene djelatnike
			'mark'  	=> str_replace(" ","_",trim(strtoupper($request['mark'])))
		);
		
		if( $request['min_days']) {
			$data += ['min_days'=> $request['min_days']];
		}
		if( $request['max_days']) {
			$data += ['max_days'=> $request['max_days']];
		}
		$absenceType = new AbsenceType();
		$absenceType->saveAbsenceType($data);
		
		session()->flash('success', __('ctrl.data_save'));
		return redirect()->back();
     //   return redirect()->route('absence_types.index');
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
        $absenceType = AbsenceType::find($id);
		
		return view('Centaur::absence_types.edit',['absenceType' => $absenceType ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AbsenceTypeRequest $request, $id)
    {
         $absenceType = AbsenceType::find($id);
		
		 $data = array(
            'name'  	=> $request['name'],
            'temp'  	=> $request['temp'], // za privremene djelatnike
			'mark'  	=> str_replace(" ","_",trim(strtoupper($request['mark'])))
		);
		if( $request['min_days']) {
			$data += ['min_days'=> $request['min_days']];
		}
		if( $request['max_days']) {
			$data += ['max_days'=> $request['max_days']];
		}
		$absenceType->updateAbsenceType($data);
		
		session()->flash('success', __('ctrl.data_edit'));
        return redirect()->back();	
//        return redirect()->route('absence_types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $absenceType = AbsenceType::find($id);
		$absenceType->delete();
		
		$message = session()->flash('success',__('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
