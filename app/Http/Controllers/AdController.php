<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdRequest;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdCategory;
use App\Models\Employee;
use App\Models\Notice;
use App\Models\Department;
use Sentinel;

class AdController extends Controller
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
    public function index(Request $request)
    {
        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
		
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		} 
		
		if(isset($request->category_id)) {
			$category = AdCategory::where('id',$request->category_id)->first();
			$ads = Ad::where('category_id',$category->id )->get();

			return view('Centaur::ads.index', ['ads' => $ads, 'category' => $category, 'permission_dep' => $permission_dep]);
		} else {
			$ads = Ad::get();
			return view('Centaur::ads.index', ['ads' => $ads, 'permission_dep' => $permission_dep]);
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$categories = AdCategory::get();
		
		if(isset($request->category_id)) {
			return view('Centaur::ads.create',['categories' => $categories, 'category_id' => $request->category_id]);
		} else {
			return view('Centaur::ads.create',['categories' => $categories]);
		}

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdRequest $request)
    {
        $user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  	=> $employee->id,
			'category_id'  	=> $request['category_id'],
			'subject'  		=> $request['subject'],
			'description'  	=> $request['description'],
			'price'  		=> $request['price'],
		);
			
		$ad = new Ad();
		$ad->saveAd($data);

        if(isset($request['fileToUpload'])) {
			$target_dir = 'storage/ads/' . $ad->id . '/';  //specifies the directory where the file is going to be placed	

			// Create directory
			if(!file_exists($target_dir)){
				mkdir($target_dir);
            }

			$target_file = $target_dir . '/' . basename($_FILES["fileToUpload"]["name"]); //$target_file specifies the path of the file to be uploaded
			if(isset($request['fileToUpload']) && file_exists($target_file)){
				array_map('unlink', glob($target_file)); // ako postoji file - briše ga
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 
			} 

			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
			// Check if image file is a actual image or fake image
			
			// Check if file already exists
			if (file_exists($target_file)) {
				return redirect()->back()->with('error', __('basic.file_exists'));  
				$uploadOk = 0;
			}
			
			/* Check file size*/
			if ($_FILES["fileToUpload"]["size"] > 5000000) {
				return redirect()->back()->with('error', __('basic.file_toolarge'));  
				$uploadOk = 0;
			}
			/* Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" && $imageFileType != "pdf") {
				return redirect()->back()->with('error', 'Dozvoljen unos samo jpg, png, pdf, gif');  
				$uploadOk = 0;
			}*/
			if($imageFileType == "exe" || $imageFileType == "bin") {
				return redirect()->back()->with('error',  __('basic.not_allowed'));  
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				return redirect()->back()->with('error', __('basic.not_uploaded')); 
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    return redirect()->route('oglasnik')->with('success', __('basic.ad') . ' ' .  __('ctrl.has_uploaded'));

				} else {
					return redirect()->route('oglasnik')->with('error', __('basic.file_error')); 
				}
			}
        }

        session()->flash('success',  __('ctrl.data_save'));
		
        return redirect()->route('oglasnik');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$ad = Ad::find($id);
		
		return view('Centaur::ads.show',['ad' => $ad ]);
    }
	
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ad = Ad::find($id);
		$categories = AdCategory::get();
	
		return view('Centaur::ads.edit',['ad' => $ad,'categories' => $categories ]);
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
        $ad = Ad::find($id);
		
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  	=> $employee->id,
			'category_id'  	=> $request['category_id'],
			'subject'  		=> $request['subject'],
			'description'  	=> $request['description'],
			'price'  		=> $request['price'],
		);
		
		$ad->updateAd($data);
		if(isset($request['fileToUpload'])) {
			$target_dir = 'storage/ads/' . $ad->id . '/';  //specifies the directory where the file is going to be placed	

			// Create directory
			if(!file_exists($target_dir)){
				mkdir($target_dir);
            }

			$target_file = $target_dir . '/' . basename($_FILES["fileToUpload"]["name"]); //$target_file specifies the path of the file to be uploaded
			if(isset($request['fileToUpload']) && file_exists($target_file)){
				array_map('unlink', glob($target_file)); // ako postoji file - briše ga
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 
			} 

			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
			// Check if image file is a actual image or fake image
			
			// Check if file already exists
			if (file_exists($target_file)) {
				return redirect()->back()->with('error', __('basic.file_exists'));  
				$uploadOk = 0;
			}
			
			/* Check file size*/
			if ($_FILES["fileToUpload"]["size"] > 5000000) {
				return redirect()->back()->with('error', __('basic.file_toolarge'));  
				$uploadOk = 0;
			}
			/* Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" && $imageFileType != "pdf") {
				return redirect()->back()->with('error', 'Dozvoljen unos samo jpg, png, pdf, gif');  
				$uploadOk = 0;
			}*/
			if($imageFileType == "exe" || $imageFileType == "bin") {
				return redirect()->back()->with('error',  __('basic.not_allowed'));  
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				return redirect()->back()->with('error', __('basic.not_uploaded')); 
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    return redirect()->route('oglasnik')->with('success', __('basic.ad') . ' ' .  __('ctrl.has_uploaded'));

				} else {
					return redirect()->route('oglasnik')->with('error', __('basic.file_error')); 
				}
			}
        }
		
		session()->flash('success',__('ctrl.data_edit'));
		
        return redirect()->route('oglasnik');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ad = Ad::find($id);
		$ad->delete();
		
		$message = session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	/**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function oglasnik(Request $request)
    {
		$user = Sentinel::getUser()->employee;

		if($user) {
			if(isset($request['sort'])) {
				$ads = Ad::orderBy('created_at', $request['sort'])->get();
			} else {
				$ads = Ad::orderBy('created_at','DESC')->get();
			}
			$user_department = array();
			$permission_dep = array();
			$departments = Department::get();
			
			$user_department = $user->work->department->id;
			$permission_dep = explode(',', count($user->work->department->departmentRole) > 0 ? $user->work->department->departmentRole->toArray()[0]['permissions'] : '');
			
			return view('Centaur::oglasnik',['ads'=> $ads,'user_department'=> $user_department,'permission_dep'=> $permission_dep, 'departments' => $departments]);

		} else {
			$message = session()->flash('error', __('ctrl.path_not_allow'));
            return redirect()->back()->withFlashMessage($message);
		}
	}
	
	public function sort ( Request $request ) {	
		if(isset($request['sort'])) {
			$ads = Ad::orderBy('created_at',$request['sort'] )->get();

			return $ads;
		}
		if(isset($request['sort_notice'])) {
			$notices = Notice::orderBy('created_at',$request['sort_notice'] )->get();
			
			return $notices;
		}
	}

}

