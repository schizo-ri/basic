<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Http\Requests\TableRequest;
use Sentinel;

class TableController extends Controller
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
        $tables = Table::orderBy('name')->get();
        $empl = Sentinel::getUser()->employee;
        $permission_dep = array();
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
        
		return view('Centaur::tables.index', ['tables' => $tables]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::tables.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TableRequest $request)
    {
        $data = array(
			'name'  		=> $request['name'],
			'description'   => $request['description'],
			'emailing'	 	=> $request['emailing']
		);
		
		$table = new Table();
		$table->saveTable($data);
		
		session()->flash('success',  __('ctrl.data_save'));
		
        return redirect()->route('tables.index');
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
        $table = Table::find($id);
		 
		return view('Centaur::tables.edit',['table' => $table]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TableRequest $request, $id)
    {
        $table = Table::find($id);
		
		$data = array(
			'name'  		=> $request['name'],
			'description'   => $request['description'],
			'emailing'	 	=> $request['emailing']
		);
		
		$table->updateTable($data);
		
		session()->flash('success', __('ctrl.data_edit'));
		
        return redirect()->route('tables.index');
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $table = Table::find($id);
		$table->delete();
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
