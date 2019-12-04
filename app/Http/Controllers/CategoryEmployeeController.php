<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryEmployeeRequest;
use App\Http\Controllers\Controller;
use App\Models\CategoryEmployee;

class CategoryEmployeeController extends Controller
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
        $categories = CategoryEmployee::orderBy('mark','ASC')->get();
        
        return view('Centaur::category_employees.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::category_employees.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryEmployeeRequest $request)
    {
        $data = array(
            'mark'          => trim($request['mark']),
            'description'   => trim($request['description'])
        );
       
        $category = new CategoryEmployee();
        $category->saveCategory($data);
        
        session()->flash('success', "Podaci su spremljeni");
        
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
        $category = CategoryEmployee::find($id);

        return view('Centaur::category_employees.edit',['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryEmployeeRequest $request, $id)
    {
        $category = CategoryEmployee::find($id);

        $data = array(
            'mark'          => trim($request['mark']),
            'description'   => trim($request['description'])
        );

        $category->updateCategory($data);

        session()->flash('success', "Podaci su ispravljeni");
        
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
        //
    }
}
