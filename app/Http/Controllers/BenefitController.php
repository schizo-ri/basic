<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Benefit;
use Sentinel;

class BenefitController extends Controller
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
        $benefits = Benefit::get();

        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 

        return view('Centaur::benefits.index', ['benefits' => $benefits,'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::benefits.create');
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
        'name'  		=> $request['name'],
        'description'   => $request['description'],
        'comment'  		=> $request['comment'],
        'url'  			=> $request['url'],
        'url2'  		=> $request['url2'],
        'status' 		=> $request['status']
        );

        $benefit = new Benefit();
        $benefit->saveBenefit($data);

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
       // $benefits = Benefit::where('status',1)->get();
        $benefits = Benefit::where('status', 1)->get();
		
		return view('Centaur::benefits.show', ['benefits' => $benefits ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $benefit = Benefit::find($id);
		
		return view('Centaur::benefits.edit', ['benefit' => $benefit ]);
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
        $benefit = Benefit::find($id);
        
        $data = array(
            'name'  		=> $request['name'],
            'description'   => $request['description'],
            'comment'  		=> $request['comment'],
            'url'  			=> $request['url'],
            'url2'  		=> $request['url2'],
            'status' 		=> $request['status']
            );

            $benefit->updateBenefit($data);
    
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
        $benefit = Benefit::find($id);
        $benefit->delete();
        
		session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back();
    }
}
