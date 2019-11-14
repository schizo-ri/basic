<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserInteres;
use Sentinel;

class UserInteresController extends Controller
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
        if($request['tag']) {
            $tag = true;
        } else {
            $tag = false;
        }

        return view('Centaur::user_interes.create',[ 'tag' => $tag]);
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
        
        if($request['category'] || $request['description'] ) {
            $data = array(
                'employee_id'  		=> $employee->id,
            );
            if($request['category']) {
                $data += ['category' => $request['category']];
            }
            if($request['description'] ) {
                $data += ['description' => $request['description']];
            }

            $uerInteres = new UserInteres();
            $uerInteres->saveUserInteres($data);
            
            session()->flash('success',  __('ctrl.data_save'));
            
            return redirect()->back();

        } else {
            $message = session()->flash('error', __('ctrl.blank_form'));
		
		    return redirect()->back()->withFlashMessage($message);
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
    public function edit(Request $request,$id)
    {

        $userInteres = UserInteres::find($id);
		if($request['tag']) {
            $tag = true;
        } else {
            $tag = false;
        }
		return view('Centaur::user_interes.edit',['userInteres' => $userInteres, 'tag' => $tag]);
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
        $userInteres = UserInteres::find($id);

        $employee = Sentinel::getUser()->employee;

        if($employee && ($request['category'] || $request['description'])) {
            $data = array(
                'employee_id'  		=> $employee->id,
            );
            if($request['category']) {
                $data += ['category' => $request['category']];
            }
            if($request['description'] ) {
                $data += ['description' => $request['description']];
            }

            $userInteres->updateUserInteres($data);

            session()->flash('success',  __('ctrl.data_edit'));
                
            return redirect()->back();

        } else {
            $message = session()->flash('error', __('ctrl.blank_form'));
        
            return redirect()->back()->withFlashMessage($message);
        }

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
