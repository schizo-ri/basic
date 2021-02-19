<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Ad;
use App\Models\AdCategory;
use App\Models\Employee;
use App\Models\Notice;
use App\Models\Department;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;
use App\Mail\AdCreateMail;
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
		$permission_dep = DashboardController::getDepartmentPermission();
		
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
		$categories = AdCategory::orderBy('name','ASC')->get();
		
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
		
		$data = array(
			'employee_id'  	=> $user->employee->id,
			'category_id'  	=> $request['category_id'],
			'subject'  		=> $request['subject'],
			'description'  	=> $request['description'],
			'price'  		=> $request['price'],
		);
			
		$ad = new Ad();
		$ad->saveAd($data);

		
        if(isset($request['fileToUpload'])) {
			$target_dir = 'storage/';
			if(!file_exists($target_dir)){
				mkdir($target_dir);
            }
			$target_dir = $target_dir.'ads/';
			if(!file_exists($target_dir)){
				mkdir($target_dir);
            }
			$target_dir = $target_dir. $ad->id . '/';
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
			if (file_exists($target_file)) {
				return redirect()->back()->with('error', __('basic.file_exists'));  
				$uploadOk = 0;
			}
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
		
			if ($uploadOk == 0) {
				return redirect()->back()->with('error', __('basic.not_uploaded')); 
		
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                   /*  return redirect()->route('oglasnik')->with('success', __('basic.image') . ' ' .  __('ctrl.has_uploaded')); */

				} else {
					return redirect()->route('oglasnik')->with('error', __('basic.file_error')); 
				}
			}
		}

		$send_to_email = array();

		/* Email adrese svim zaposlenika */
	 	$send_to_email = Employee::getEmails();
		try {
			foreach ($send_to_email as $email) {
				Mail::to($email)->send(new AdCreateMail($ad));
			}
		} catch (\Throwable $th) {
			$email = 'jelena.juras@duplico.hr';
			$url = $_SERVER['REQUEST_URI'];
			Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
			session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
			return redirect()->back();
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
		
		$data = array(
			'employee_id'  	=> $user->employee->id,
			'category_id'  	=> $request['category_id'],
			'subject'  		=> $request['subject'],
			'description'  	=> $request['description'],
			'price'  		=> $request['price'],
		);
		
		$ad->updateAd($data);
		if(isset($request['fileToUpload'])) {
			$target_dir = 'storage';
			if(!file_exists($target_dir)){
				mkdir($target_dir);
            }
			$target_dir = $target_dir.'/ads/';
			if(!file_exists($target_dir)){
				mkdir($target_dir);
            }
			$target_dir = $target_dir . $ad->id . '/';  //specifies the directory where the file is going to be placed	
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
			if (file_exists($target_file)) {
				return redirect()->back()->with('error', __('basic.file_exists'));  
				$uploadOk = 0;
			}
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
			if ($uploadOk == 0) {
				return redirect()->back()->with('error', __('basic.not_uploaded')); 
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                   // return redirect()->route('oglasnik')->with('success', __('basic.ad') . ' ' .  __('ctrl.has_uploaded'));

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
		if($ad) {
			$ad->delete();
		}

		$target_dir = 'storage/ads/' . $ad->id . '/';
			
			if(file_exists($target_dir)){
				array_map('unlink', glob("$target_dir/*.*"));
			}
			if(file_exists($target_dir)){
				rmdir($target_dir);
			}
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
			$permission_dep = DashboardController::getDepartmentPermission();
			
			if( $user->work){
				$user_department = $user->work->department->id;
			}
			
			return view('Centaur::oglasnik',['ads'=> $ads,'user_department'=> $user_department,'permission_dep'=> $permission_dep]);

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

