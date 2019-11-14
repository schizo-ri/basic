<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DepartmentRole;
use App\Models\Department;
use App\Models\Table;
use Sentinel;

class DepartmentRoleController extends Controller
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
		$department_roles = DepartmentRole::get();
		$empl = Sentinel::getUser()->employee;
        $permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
		
		return view('Centaur::department_roles.index', ['department_roles' => $department_roles, 'permission_dep' => $permission_dep]);
	}
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$tables1 = Table::get();
		$tables = array();
		
		foreach($tables1 as $table) {
			array_push($tables,$table->name);
		}
		
		$methodes = array('create','update','view','delete');
		
		if(isset($request->department_id)) {
			$department = Department::find($request->department_id);
			return view('Centaur::department_roles.create',['tables' => $tables, 'methodes' => $methodes, 'department' => $department]);
		}else {
			$departments = Department::get();
			$department_roles = DepartmentRole::get();
			return view('Centaur::department_roles.create',['tables' => $tables, 'methodes' => $methodes, 'departments' => $departments, 'department_roles' => $department_roles]);
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permissions = [];
		
        foreach ($request->get('permissions', []) as $permission => $value) {
            array_push($permissions,$permission);
        }

		$data = array(
			'department_id' => $request['department_id'],
			'permissions'  => implode(',', $permissions),
		);
		
		$departmentRole = new DepartmentRole();
		$departmentRole->saveDepartmentRole($data);
		
		session()->flash('success',  __('ctrl.data_save'));
		
        return redirect()->route('department_roles.index');
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
	    $departmentRole = DepartmentRole::find($id);

		$permissions = explode(',', $departmentRole->permissions);

		$tables1 = Table::get();
		$tables = array();
		
		foreach($tables1 as $table) {
			array_push($tables,$table->name);
		}
		$methodes = array('create','update','view','delete');
		
		return view('Centaur::department_roles.edit',['departmentRole' => $departmentRole,'tables' => $tables, 'methodes' => $methodes, 'permissions' => $permissions]);
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
        $departmentRole = DepartmentRole::find($id);
		
		$permissions = [];
		
        foreach ($request->get('permissions', []) as $permission => $value) {
            array_push($permissions,$permission);
        }

		$data = array(
			'department_id' => $request['department_id'],
			'permissions'  => implode(',', $permissions),
		);
		
		$departmentRole->updateDepartmentRole($data);
		
		session()->flash('success', __('ctrl.data_edit'));
		
        return redirect()->route('department_roles.index');
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departmentRole = DepartmentRole::find($id);
		
		$departmentRole->delete();
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
