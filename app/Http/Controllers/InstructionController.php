<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Instruction;
use App\Models\Department;
use App\Mail\IstructionMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;
use Sentinel;

class InstructionController extends Controller
{
    /**
     * Set middleware to quard controller.
     *
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
        $instructions = Instruction::orderBy('title','ASC')->get();
       
        $permission_dep = DashboardController::getDepartmentPermission();

        return view('Centaur::instructions.index', ['instructions' => $instructions,'permission_dep' => $permission_dep]);
    }

    public function radne_upute () 
    {
        $employee = Sentinel::getUser()->employee;
        if ( $employee ) {
            $employee_departments = $employee->employeesDepartment();
        } else {
            $employee_departments = array();
        }
       
        $permission_dep = DashboardController::getDepartmentPermission();
        $instructions = Instruction::orderBy('title','ASC')->where('active',1)->get();
     
        return view('Centaur::radne_upute', ['instructions' => $instructions,'permission_dep' => $permission_dep,'employee_departments' => $employee_departments]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::orderBy('name','ASC')->get();

        return view('Centaur::instructions.create', ['departments' => $departments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $send_to = array('jelena.juras@duplico.hr');

        foreach ($request['department_id'] as $department_id) {
            $data = array(
                'department_id' => $department_id,
                'title'         => $request['title'],
                'description'   => $request['description'],
                'active'        => $request['active']
            );
            
            $instruction = new Instruction();
            $instruction->saveInstruction($data); 

        //    array_push( $prima, Department::allDepartmentsEmployeesEmail( $department_id ));
        }
        
        try {
            foreach (array_unique($send_to) as $send_to_mail) {
                Mail::to($send_to_mail)->send(new IstructionMail($instruction)); 
            }                    
        } catch (\Throwable $th) {
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 

            $message = session()->flash('success',  __('emailing.not_send'));
            return redirect()->back()->withFlashMessage($message);
        }

        session()->flash('success',  __('ctrl.data_save') .' ' . __('ctrl.sent_message') );
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
        $instruction = Instruction::find($id);

        return view('Centaur::instructions.show', ['instruction' => $instruction]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $instruction = Instruction::find($id);

        $departments = Department::orderBy('name','ASC')->get();

        return view('Centaur::instructions.edit', ['instruction' => $instruction,'departments' => $departments]);
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
        $instruction = Instruction::find($id);

        $data = array(
            'department_id' => $request['department_id'],
            'title'         => $request['title'],
            'description'   => $request['description'],
            'active'        => $request['active']
        );
        
        
        $instruction->updateInstruction($data);

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
        $instruction = Instruction::find($id);
        $instruction->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();

    }
}
