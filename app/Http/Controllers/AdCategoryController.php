<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdCategoryRequest;
use App\Http\Controllers\Controller;
use App\Models\AdCategory;
use Sentinel;

class AdCategoryController extends Controller
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
        $categories = AdCategory::get();
		$empl = Sentinel::getUser()->employee;
        $permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
        
		return view('Centaur::ad_categories.index', ['categories' => $categories,'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::ad_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdCategoryRequest $request)
    {
        $data = array(
			'name'  	=> $request['name']
		);
			
		$adCategory = new AdCategory();
		$adCategory->saveCategory($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('ad_categories.index');
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
		$category = AdCategory::find($id);

		return view('Centaur::ad_categories.edit',['category' => $category ]);
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
        $category = AdCategory::find($id);
		
		 $data = array(
			'name'  	=> $request['name']
		);
			
		$category->updateCategory($data);
		
		session()->flash('success', __('ctrl.data_edit'));
		
        return redirect()->route('ad_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = AdCategory::find($id);
		$category->delete();
		
		$message = session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
