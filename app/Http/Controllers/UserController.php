<?php

namespace App\Http\Controllers;

use Mail;
use Sentinel;
use App\Http\Requests;
use Centaur\AuthManager;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Work;
use App\User;
use App\Models\UserInteres;
use App\Models\Department;
use App\Models\DepartmentRole;
use Centaur\Mail\CentaurWelcomeEmail;
use Cartalyst\Sentinel\Users\IlluminateUserRepository;
use App\Http\Controllers\CompanyController; 
use App\Http\Controllers\BasicAbsenceController; 

class UserController extends Controller
{
    /** @var Cartalyst\Sentinel\Users\IlluminateUserRepository */
    protected $userRepository;

    /** @var Centaur\AuthManager */
    protected $authManager;

    public function __construct(AuthManager $authManager)
    {
        // Middleware
        $this->middleware('sentinel.auth');
        $this->middleware('sentinel.access:users.create', ['only' => ['create', 'store']]);
        $this->middleware('sentinel.access:users.view', ['only' => ['index', 'show']]);
        $this->middleware('sentinel.access:users.update', ['only' => ['edit', 'update']]);
        $this->middleware('sentinel.access:users.destroy', ['only' => ['destroy']]);

        // Dependency Injection
        $this->userRepository = app()->make('sentinel.users');
        $this->authManager = $authManager;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->createModel()->orderBy('last_name', 'ASC')->with('roles')->leftJoin('employees', 'users.id', '=', 'employees.user_id')->select('users.*','employees.b_day','employees.work_id')->get();
        $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
		$departmentRoles = DepartmentRole::get();
		$works = Work::get();
		$empl = Sentinel::getUser()->employee;
        $permission_dep = array();
		if($empl) {
            if($empl->work && $empl->work->department) {
                $permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
            }			
        } 
        $roles = app()->make('sentinel.roles')->createModel()->all();

        return view('Centaur::users.index', ['users' => $users, 'employees' => $employees, 'works' => $works, 'departmentRoles' => $departmentRoles,'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = app()->make('sentinel.roles')->createModel()->all();

        return view('Centaur::users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // Validate the form data
         $result = $this->validate($request, [
            'email' => 'required|max:255|unique:users',
            'password' => 'required|confirmed|min:4',
        ]);

        // Assemble registration credentials and attributes
        $credentials = [
            'email' => trim($request->get('email')),
            'password' => $request->get('password'),
            'first_name' => $request->get('first_name', null),
            'last_name' => $request->get('last_name', null)
        ];
        $activate = (bool)$request->get('activate', false);

        // Attempt the registration
        $result = $this->authManager->register($credentials, $activate);

        if ($result->isFailure()) {
            return $result->dispatch;
        }

        // Do we need to send an activation email?
        if (! $activate ) {
            $code = $result->activation->getCode();
            $email = $result->user->email;
            try {
                Mail::to($email)->queue(new CentaurWelcomeEmail($email, $code));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error',  __('ctrl.no_valid_email')); 
            }            
        }
        // Assign User Roles
        foreach ($request->get('roles', []) as $slug => $id) {
            $role = Sentinel::findRoleBySlug($slug);
            if ($role) {
                $role->users()->attach($result->user);
            }
        }

        $data_employee = [
            'user_id' => User::orderBy('id','DESC')->first()->id,
            'email'   => User::orderBy('id','DESC')->first()->email,
            'work_id' => Work::orderBy('id','ASC')->first()->id,
            'reg_date' => date('Y-m-d'),
        ];

        $employee = new Employee();
        $employee->saveEmployee( $data_employee );

        if ($request->hasFile('fileToUpload')) {
            $image = $request->file('fileToUpload');
            $user_name = $request['last_name'] . '_' . $request['first_name'];
            if(isset($request['email'])) {
                $user_name = explode('.',strstr($request['email'],'@',true));
                if(isset($user_name[1])) {
                    $user_name = $user_name[1] . '_' . $user_name[0];
                } else {
                    $user_name = $user_name[0];
                }         
                
            }

            $path = 'storage/';
            if (!file_exists($path)) {
                mkdir($path);
            }
            $path = $path  . $user_name;
            if (!file_exists($path)) {
                mkdir($path);
            }
            $path =  $path . '/profile_img/';
            if (!file_exists($path)) {
                mkdir($path);
            }
            $docName = $request->file('fileToUpload')->getClientOriginalName();  //file name
            
            try {
                $request->file('fileToUpload')->move($path, $docName);
                DocumentController::createResizedImage($path . $docName, $path . pathinfo($docName)['filename'] . '_small.' . pathinfo($docName)['extension'], 200, 250);

            /*     return redirect()->back()->with('success', __('ctrl.uploaded')); */
  
              } catch (\Throwable $th) {
                return redirect()->back()->with('error',  __('ctrl.not_uploaded')); 
              }
        }
       
        session()->flash('success',"User {$request->get('email')} has been created.");
        return redirect()->back();
    }

    /**
     * Display the specified user.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {       
        $user = $this->userRepository->createModel()->with('roles')->find($id);
        $departmentRoles = DepartmentRole::get();
       
		$empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
        $image_employee = null;
        $user_name = null;
        $work = null;
        $dep_roles = null;
        $data_absence  = null;
        $requests = null;
        $yearsRequests = null;
        $bolovanje = null;
        $years = array();

		if($empl) {
            $permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');

            if($user->employee) {
                $image_employee = DashboardController::profile_image($user->employee->id);
                $user_name = DashboardController::user_name($user->employee->id);
                $work = $user->employee->work;
                    
                if($work && $departmentRoles->where('department_id', $work->id)->first()) {
                    $dep_roles = explode(  ',', $departmentRoles->where('department_id', $work->id)->first()->permissions);
                }

                $data_absence = array(
                    'years_service'  => BasicAbsenceController::yearsServiceCompany( $user->employee ),  
                    'all_servise'  	=> BasicAbsenceController::yearsServiceAll( $user->employee ), 
                    'days_OG'  		=> BasicAbsenceController::daysThisYear( $user->employee ), 
                    'razmjeranGO'  	=> BasicAbsenceController::razmjeranGO( $user->employee ),  //razmjeran go ova godina
                    'zahtjevi' 		 => BasicAbsenceController::requestAllYear( $user->employee ), 
                );
                
                $requests = BasicAbsenceController::zahtjevi($user->employee);
                $yearsRequests = BasicAbsenceController::yearsRequests($user->employee);
                $bolovanje =BasicAbsenceController::bolovanje($user->employee);
                $years = BasicAbsenceController::yearsRequests($user->employee); // sve godine zahtjeva
            }
        }

        return view('Centaur::users.show', ['user' => $user, 'departmentRoles' => $departmentRoles, 'permission_dep' => $permission_dep, 'image_employee' => $image_employee,'years' => $years, 'user_name' => $user_name, 'data_absence' => $data_absence, 'work' => $work, 'dep_roles' => $dep_roles,'requests' => $requests,'yearsRequests' => $yearsRequests,'bolovanje' => $bolovanje]);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Fetch the user object
        // $id = $this->decode($hash);
        $user = $this->userRepository->findById($id);

        // Fetch the available roles
        $roles = app()->make('sentinel.roles')->createModel()->all();
        
        if ($user) {
            return view('Centaur::users.edit', [
                'user' => $user,
                'roles' => $roles
            ]);
        }

        
        session()->flash('error', __('ctrl.invalid_user') );
        return redirect()->back();
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the form data
     /*    $result = $this->validate($request, [
            'email' => 'required|max:255|unique:users,email,'.$id,
        ]); */

        // Assemble the updated attributes
        $attributes = [
            'email' => trim($request->get('email')),
            'first_name' => $request->get('first_name', null),
            'last_name' => $request->get('last_name', null)
        ];

        // Do we need to update the password as well?
        if ($request->has('password')  && $request->get('password') != null) {
          /*   $result = $this->validate($request, [
				'password' => 'nullable|confirmed|min:5',
			]); */
			$attributes['password'] = $request->get('password');
        }

        // Fetch the user object
        $user = $this->userRepository->findById($id);
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json("Invalid user.", 422);
            }
            session()->flash('error', __('ctrl.invalid_user'));
            return redirect()->back()->withInput();
        }

        // Update the user
        $user = $this->userRepository->update($user, $attributes);

        if ($request->hasFile('fileToUpload')) {
            $image = $request->file('fileToUpload');
            $user_name = $request['last_name'] . '_' . $request['first_name'];
            if(isset($request['email'])) {
                $user_name = explode('.',strstr($request['email'],'@',true));
                if(isset($user_name[1])) {
                    $user_name = $user_name[1] . '_' . $user_name[0];
                } else {
                    $user_name = $user_name[0];
                }         
                
            }

            $path = 'storage/';
            if (!file_exists($path)) {
                mkdir($path);
            }
            $path = $path  . $user_name;
            if (!file_exists($path)) {
                mkdir($path);
            }
            $path =  $path . '/profile_img/';
            if (!file_exists($path)) {
                mkdir($path);
            }
            $docName = $request->file('fileToUpload')->getClientOriginalName();  //file name
            
            try {
                $request->file('fileToUpload')->move($path, $docName);
                DocumentController::createResizedImage($path . $docName, $path . pathinfo($docName)['filename'] . '_small.' . pathinfo($docName)['extension'], 200, 250);

            /*     return redirect()->back()->with('success', __('ctrl.uploaded')); */
  
              } catch (\Throwable $th) {
                return redirect()->back()->with('error',  __('ctrl.not_uploaded')); 
              }
        }

        // Update role assignments
        $roleIds = array_values($request->get('roles', []));
        $user->roles()->sync($roleIds);

        // All done
        if ($request->expectsJson()) {
            return response()->json(['user' => $user], 200);
        }

        session()->flash('success', $user->email . ' ' . __('auth.auth_update'));
        return redirect()->back();
        //    return redirect()->route('users.index');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Fetch the user object
        //$id = $this->decode($hash);
        $user = $this->userRepository->findById($id);

        // Check to be sure user cannot delete himself
        if (Sentinel::getUser()->id == $user->id) {
            $message = @lang('ctrl.delete_self') ;

            if ($request->expectsJson()) {
                return response()->json($message, 422);
            }
            session()->flash('error', $message);
            return redirect()->route('users.index');
        }


        // Remove the user
        $user->delete();

        // All done
        $message = "{$user->email}" . __('ctrl.removed');
        if ($request->expectsJson()) {
            return response()->json([$message], 200);
        }

        session()->flash('success', $message);
        return redirect()->route('users.index');
    }

    /**
     * Decode a hashid
     * @param  string $hash
     * @return integer|null
     */
    // protected function decode($hash)
    // {
    //     $decoded = $this->hashids->decode($hash);

    //     if (!empty($decoded)) {
    //         return $decoded[0];
    //     } else {
    //         return null;
    //     }
    // }

    /**
     * Show the form for editing the specified user.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function edit_user($id)
    {
        // Fetch the user object
        // $id = $this->decode($hash);
        $user = $this->userRepository->findById($id);
		
        $employee =  $user->employee;

		$departments = Department::orderBy('name','ASC')->get();
		
        // Fetch the available roles
        $roles = app()->make('sentinel.roles')->createModel()->all();
        
        if ($user) {
            if(isset($employee)) {
                $user_interes = UserInteres::where('employee_id',$employee->id)->first();
                if( $user_interes ) {
                    if($user_interes->category != null) {
                        $interes_tags = explode(',', $user_interes->category);
                    } else {
                        $interes_tags = array();
                    }
                    $interes_info = $user_interes->description;
                } else {
                    $interes_tags = array();
                    $user_interes = array();
                    $interes_info = '';
                }
                
                $images_interest = array();
                $images_interesting_fact = array();
                $path = '';
                $path2 = '';
                if($employee->email) {
                    $user_name = explode('.',strstr($employee->email,'@',true));
                    if(isset($user_name[1])) {
                        $user_name = $user_name[1] . '_' . $user_name[0];
                    } else {
                        $user_name = $user_name[0];
                    }
                   
                    $path = 'storage/' . $user_name . '/interest/';
                    $path2 = 'storage/' . $user_name . '/interesting_fact/';
                    
                    if(file_exists($path)){
                      $images_interest = array_diff(scandir($path), array('..', '.', '.gitignore'));
                    }
    
                    if(file_exists($path2)){
                      $images_interesting_fact = array_diff(scandir($path2), array('..', '.', '.gitignore'));
                    }
                } else {
                    $user_name = explode('.',strstr(Sentinel::getUser()->email,'@',true));
                    
                    if(count($user_name) == 2) {
                        $user_name = $user_name[1] . '_' . $user_name[0];
                    } else {
                        $user_name = $user_name[0];
                    }
                    $path = 'storage/' . $user_name . '/interest/';
                    $path2 = 'storage/' . $user_name . '/interesting_fact/';
                    
                    if(file_exists($path)){
                      $images_interest = array_diff(scandir($path), array('..', '.', '.gitignore'));
                    }
    
                    if(file_exists($path2)){
                      $images_interesting_fact = array_diff(scandir($path2), array('..', '.', '.gitignore'));
                    }
                }
               
                return view('Centaur::users.edit_user', [
                    'user' => $user,
                    'employee' => $employee,
                    'departments' => $departments,
                    'roles' => $roles,
                    'path' => $path,
                    'path2' => $path2,
                    'images_interest' => $images_interest,
                    'images_interesting_fact' => $images_interesting_fact,
                    'user_interes' => $user_interes,
                    'interes_info' => $interes_info,
                    'interes_tags' => $interes_tags,
                ]);
            } else {
                session()->flash('error', __('ctrl.not_registered'));
                return redirect()->back();
            }
        }

        session()->flash('error', __('auth.invalid_user'));
        return redirect()->back();
    }


    public function slide_show($id)
    {
        $employee = Sentinel::getUser()->employee;
       
        if(isset($employee)) {
            $images_interest = array();
            $path = '';
            if($employee->email) {
                $user_name = explode('.',strstr($employee->email,'@',true));

                if(count($user_name) == 2) {
                    $user_name = $user_name[1] . '_' . $user_name[0];
                } else {
                    $user_name = $user_name[0];
                }
    
                $path = 'storage/' . $user_name . '/interest/';
                
                if(file_exists($path)){
                    $images_interest = array_diff(scandir($path), array('..', '.', '.gitignore'));
                }
            }

            return view('Centaur::users.slide_show', [
                'employee' => $employee,
                'path' => $path,
                'images_interest' => $images_interest,
                'id'    => $id     
            ]);
        } else {
            session()->flash('error', __('ctrl.not_registered'));
            return redirect()->back();
        }
    }

}
