<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Http\Controllers\Controller;
use App\Models\Employee;


class EmployeeController extends Controller
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
        $employees = Employee::orderBy('last_name','ASC')->get();
        
        return view('Centaur::employees.index',['employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::employees.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        
        $data = array(
			'first_name' => trim($request['first_name']),
			'last_name'  => trim($request['last_name'])
        );
       
        $employee = new Employee();
        $employee->saveEmployee($data);
        
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
        $employee = Employee::find($id);

        return view('Centaur::employees.edit',['employee' => $employee]);
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
        $employee = Employee::find($id);

        $data = array(
			'first_name' => trim($request['first_name']),
			'last_name'  => trim($request['last_name'])
        );

        $employee->updateEmployee($data);

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
        $employee = Employee::find($id);
		$employee->delete();
		
		$message = session()->flash('success',  "Djelatnik je obrisan");
		
		return redirect()->back()->withFlashMessage($message);
    }
}
