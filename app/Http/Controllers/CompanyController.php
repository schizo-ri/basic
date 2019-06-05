<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;

class CompanyController extends Controller
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
        $companies = Company::get();
		$departments = Department::get();
		
		return view('Centaur::companies.index', ['companies' => $companies, 'departments' => $departments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $data = array(
			'name'  		=> $request['name'],
			'address'  		=> $request['address'],
			'city'  		=> $request['city'],
			'oib'  			=> $request['oib'],
			'director'  	=> $request['director'],
			'email'  		=> trim($request['email']),
			'phone'  		=> $request['phone']
		);
		
		$company = new Company();
		$company->saveCompany($data);
		
		if(isset($request['fileToUpload'])) {
			$target_dir = 'storage/company_img/';  //specifies the directory where the file is going to be placed	

			// Create directory
			if(!file_exists($target_dir)){
				mkdir($target_dir);
			}

			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); //$target_file specifies the path of the file to be uploaded
			if(isset($request['fileToUpload']) && file_exists($target_file)){
				array_map('unlink', glob($target_file)); // ako postoji file - briše ga
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 
			} 

			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
			// Check if image file is a actual image or fake image
			
			// Check if file already exists
			if (file_exists($target_file)) {
				return redirect()->back()->with('error', 'Sorry, file already exists.');  
				$uploadOk = 0;
			}
			
			/* Check file size*/
			if ($_FILES["fileToUpload"]["size"] > 500000) {
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
					return redirect()->back()->with('success',"The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
				} else {
					return redirect()->route('Centaur::companies.index')->with('error', 'Sorry, there was an error uploading your file.'); 
				}
			}
		}
		
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('Centaur::companies.index');
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
        $company = Company::find($id);
		
		return view('Centaur::companies.edit', ['company' => $company]);
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
        $company = Company::find($id);
		
		$data = array(
			'name'  		=> $request['name'],
			'address'  		=> $request['address'],
			'city'  		=> $request['city'],
			'oib'  			=> $request['oib'],
			'director'  	=> $request['director'],
			'email'  		=> trim($request['email']),
			'phone'  		=> $request['phone']
		);
		
		$company->updateCompany($data);
		
		if(isset($request['fileToUpload'])) {
			$target_dir = 'storage/company_img/';  //specifies the directory where the file is going to be placed	

			// Create directory
			if(!file_exists($target_dir)){
				mkdir($target_dir);
			}
			
			$extension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
			
			$target_file = $target_dir . 'logo' . '.' . $extension; //$target_file specifies the path of the file to be uploaded
			if(isset($request['fileToUpload']) && file_exists($target_file)){
				unlink($target_file); // ako postoji file - briše ga
				$target_file = $target_dir . 'logo' . '.' . $extension ; 
			} 

			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));  //holds the file extension of the file (in lower case)
			// Check if image file is a actual image or fake image
			
			// Check if file already exists
			if (file_exists($target_file)) {
				return redirect()->back()->with('error', 'Sorry, file already exists.');  
				$uploadOk = 0;
			}
			
			/* Check file size*/
			if ($_FILES["fileToUpload"]["size"] > 500000) {
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
					return redirect()->route('companies.index')->with('success',"The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
				} else {
					return redirect()->route('Centaur::companies.index')->with('error', 'Sorry, there was an error uploading your file.'); 
				}
			}
		}
		
		session()->flash('success', "Podaci su ispravljeni");
		
        return redirect()->route('companies.index');
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::find($id);
		$company->delete();
		
		$message = session()->flash('success', 'Tvrtka je obrisana.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
