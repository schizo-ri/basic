<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Employee;
use Sentinel;

class DocumentController extends Controller
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
		$user = Employee::where('user_id',Sentinel::getUser()->id)->first();
		$empl = Sentinel::getUser()->employee;
		$permission_dep = array();

		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		}
		
		if(isset($user)) {
			
			$user_name = explode('.',strstr($user->email,'@',true));
			if(count($user_name) == 2) {
				$user_name = $user_name[1] . '_' . $user_name[0];
			}else {
				$user_name = $user_name[0];
			}
			$documents = Document::where('path','like','%'.$user_name .'%')->orWhere('path','like','%svi%')->get();
			$employees = Employee::get();
			
			$path = 'storage/' . $user_name . '/documents/';
			
			if(file_exists($path)){
				$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
				
			}else {
				$docs = array();
			}
			
			$path2 = 'storage/svi/';
			if(file_exists($path2)){
				$docs2 = array_diff(scandir($path2), array('..', '.', '.gitignore'));
			}else {
				$docs2 = array();
			}
			
			return view('Centaur::documents.index', ['docs' => $docs,'docs2' => $docs2,'documents' => $documents, 'employees' => $employees, 'user_name' => $user_name, 'permission_dep' => $permission_dep]);
		} else {
			$message = session()->flash('error', 'Putanja nije dozvoljena');
			return redirect()->back()->withFlashMessage($message);
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$employees = Employee::where('checkout',null)->get();
		
		return view('Centaur::documents.create',['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee = Employee::where('id',$request->employee_id)->first();

		if($request['employee_id'] == 'svi'){
			$user_name = 'svi';
		} else {
			$user_name = explode('.',strstr($employee->email,'@',true));
			
			$user_name = $user_name[1] . '_' . $user_name[0];
		}
		

		if(isset($request['profileIMG'])) {
			$path = 'storage/' . $user_name . "/profile_img/";  //specifies the directory where the file is going to be placed
			$files = glob($path .'*'); // get all file names
			foreach($files as $file){ // iterate files
			  if(is_file($file))
				unlink($file); // delete file
			}
				// Create directory
				if(!file_exists($path)){
					mkdir($path);
				}
		} else {
			$path = 'storage/' . $user_name . '/';
			if (!file_exists($path)) {
				mkdir($path);
			}
			
			$path = 'storage/' . $user_name . '/documents/';
			if (!file_exists($path)) {
				mkdir($path);
			}
		}

		$target_file = $path . basename($_FILES["fileToUpload"]["name"]); //$target_file specifies the path of the file to be uploaded
		
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
		// Check if image file is a actual image or fake image

		// Check if file already exists
		if (file_exists($target_file)) {
			return redirect()->back()->with('error', 'Sorry, file already exists.');  
			$uploadOk = 0;
		}
		/* Check file size*/
		if ($_FILES["fileToUpload"]["size"] > 5000000) {
			return redirect()->back()->with('error', 'Sorry, your file is too large.');  
			$uploadOk = 0;
		}
		/* Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" && $imageFileType != "pdf") {
			return redirect()->back()->with('error', 'Dozvoljen unos samo jpg, png, pdf, gif');  
			$uploadOk = 0;
		}*/
		if($imageFileType == "exe" || $imageFileType == "bin") {
			return redirect()->back()->with('error', 'Nije dozvoljen unos exe, bin dokumenta');  
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			return redirect()->back()->with('error', 'Sorry, your file was not uploaded.'); 
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				
				$user = Employee::where('user_id', Sentinel::getUser()->id)->first();
				
				$data = array(
					'employee_id'  	=> $user->id,
					'title'  		=> basename($_FILES["fileToUpload"]["name"]),
					'path'  		=> $path
				);
				
				$document = new Document();
				$document->saveDocument($data);
				
				return redirect()->back()->with('success',"The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
				
				
			} else {
				return redirect()->back()->with('error', 'Sorry, there was an error uploading your file.'); 
			}
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
    public function edit($id)
    {
        //
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
        //
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
