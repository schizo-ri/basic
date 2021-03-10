<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentController;
use App\Models\Benefit;
use Sentinel;

class BenefitController extends Controller
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
        $benefits = Benefit::get();

        return view('Centaur::benefits.index', ['benefits' => $benefits]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::benefits.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array(
            'title'  		=> $request['title'],
            'description'   => $request['description'],
            'contact'  		=> $request['contact'],
            'email'  		=> $request['email'],
            'phone'  		=> $request['phone'],
            'status' 		=> $request['status']
        );

        $benefit = new Benefit();
        $benefit->saveBenefit($data);

        /* upload file */
        if($_FILES["fileToUpload"]) {
            $target_dir = "img/benefits/";
            // Create directory
            if(!file_exists($target_dir)){
                mkdir($target_dir);
            }
            $target_dir.= $benefit->id ."/";
            if(!file_exists($target_dir)){
                mkdir($target_dir);
            }
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                return redirect()->back()->with('error', __('ctrl.file_exists'));  
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 5000000) {
                return redirect()->back()->with('error',  __('ctrl.file_toolarge'));
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "svg" ) {
                return redirect()->back()->with('error', __('ctrl.not_allow'));  
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                return redirect()->back()->with('error',  __('ctrl.not_uploaded')); 
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    return redirect()->back()->with('success', __('basic.image') . ' ' . basename( $_FILES["fileToUpload"]["name"]).  __('ctrl.has_uploaded'));
                } else {
                    return redirect()->back()->with('error',  __('ctrl.file_error')); 
                }
            }
            
        }
        
        session()->flash('success',  __('ctrl.data_save'));

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
       // $benefits = Benefit::where('status',1)->get();
        $benefits = Benefit::get();
        
		return view('Centaur::benefits.show', ['benefits' => $benefits]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $benefit = Benefit::find($id);
		
		return view('Centaur::benefits.edit', ['benefit' => $benefit ]);
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
        $benefit = Benefit::find($id);
        
        $data = array(
            'title'  		=> $request['title'],
            'description'   => $request['description'],
            'contact'  		=> $request['contact'],
            'email'  		=> $request['email'],
            'phone'  		=> $request['phone'],
            'status' 		=> $request['status']
        );

        $benefit->updateBenefit($data);

        /* upload file */
        if($_FILES["fileToUpload"]['name'] ) {
            $target_dir = "img/benefits/";
            // Create directory
            if(!file_exists($target_dir)){
                mkdir($target_dir);
            }
            $target_dir.= $benefit->id ."/";
            if(!file_exists($target_dir)){
                mkdir($target_dir);
            }
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                return redirect()->back()->with('error', __('ctrl.file_exists'));  
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 5000000) {
                return redirect()->back()->with('error',  __('ctrl.file_toolarge'));
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "svg" ) {
                return redirect()->back()->with('error', __('ctrl.not_allow'));  
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                return redirect()->back()->with('error',  __('ctrl.not_uploaded')); 
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    return redirect()->back()->with('success',__('basic.image') . ' ' . basename( $_FILES["fileToUpload"]["name"]).  __('ctrl.has_uploaded'));
                } else {
                    return redirect()->back()->with('error',  __('ctrl.file_error')); 
                }
            }
            
        }
         
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
        $benefit = Benefit::find($id);
        $benefit->delete();
        
		session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back();
    }
}