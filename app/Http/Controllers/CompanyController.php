<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Mail\ErrorMail;
use Illuminate\Support\Facades\Mail;
use DB;
use Sentinel;

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
		$modules = $this->getModules(); //moduli iz superadmin baze
		$empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
		
		return view('Centaur::companies.index', ['companies' => $companies, 'departments' => $departments, 'modules' => $modules, 'permission_dep' => $permission_dep]);
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
		
		
		session()->flash('success',  __('ctrl.data_save'));
		return redirect()->back();	
     //  return redirect()->route('Centaur::companies.index');
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
					session()->flash('success', "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
					return redirect()->back();	
				//	return redirect()->route('companies.index')->with('success',"The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
				} else {
					session()->flash('error', 'Sorry, there was an error uploading your file.');
					return redirect()->back();	
				//	return redirect()->route('Centaur::companies.index')->with('error', 'Sorry, there was an error uploading your file.'); 
				}
			}
		}
		
		session()->flash('success', __('ctrl.data_edit'));
		return redirect()->back();	
    //    return redirect()->route('companies.index');
		
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
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	public static function getModules () 
	{
		$moduli_company = array(); 
		$company = Company::first();
		
		if ($company) {
			$company_oib = $company->oib;
		
			if($company_oib) {
				//konektiranje na superadmin bazu
				$db_ext = DB::connection('mysql_external'); 
				//dohvaćanje iz tbl zahtjeva zahtjev korisnika
				try {
					/* $client_request = $db_ext->table('clients')->join('client_requests','clients.id','client_requests.client_id')->where('clients.oib', $company_oib)->select('clients.*','client_requests.modules')->orderBy('clients.updated_at','DESC')->first(); */
					$client = $db_ext->table('clients')->where('clients.oib', $company_oib)->first();
				
					$client_request = $db_ext->table('client_requests')->where('client_id', $client->id)->first();
					
					/* 	$client_request = $db_ext->table('client_requests')->join('clients','client_requests.client_id','clients.id')->select('client_requests.modules','clients.*')->where('clients.oib',$company_oib)->orderBy('client_requests.updated_at','DESC')->first(); */
					if($client_request) { 
						$modules = explode(',', $client_request->modules);
					
						$moduli = $db_ext->table('modules')->get(); //dobvaćanje modula
						
						foreach( $modules as $module){
							array_push($moduli_company,$moduli->where('id', $module)->first()->name); // array sa nazivima  modulima korisnika
						}
					
					}
				} catch (Exception $e) {
					$email = 'jelena.juras@duplico.hr';
                    $url = $_SERVER['REQUEST_URI'];
					Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
					
					session()->flash('error', __('ctrl.error'));
					return redirect()->back();
				}
			}
		}
	
/* {#685 ▼
  +"id": 19
  +"name": "Sportkart d.o.o."
  +"address": "Avenija Dubrovnik 15/33"
  +"city": "Zagreb"
  +"oib": "20156458678"
  +"first_name": "Igor"
  +"last_name": "Tabak"
  +"email": "igor.tabak@karting-arena.com"
  +"phone": "778 7534"
  +"created_at": "2020-05-22 07:43:05"
  +"updated_at": "2020-05-22 07:43:05"
}*/

/* {#202 ▼
	+"id": 13
	+"client_id": 19
	+"modules": "1,2,3,4,5,6,7,8,9,10"
	+"db": "myintran_sportkart"
	+"url": "sportkart.myintranet.io"
	+"price_per_user": null
	+"no_users": 100
	+"calculate_method": null
	+"created_at": "2020-05-22 07:43:05"
	+"updated_at": "2020-05-22 07:43:05"
  }
   */
		return $moduli_company;
	}

	public static function getCompanyURL() {
		$url = array( 'SERVER_NAME' => $_SERVER['SERVER_NAME'],'SERVER_NAME'=>  $_SERVER['SERVER_NAME'], 'host' => $_SERVER['HTTP_HOST'], 'uri' => $_SERVER['REQUEST_URI'],);
		
		return $url;
	}

	public static function getUsersNumber() {
		$company = Company::first();
		
		if($company) {
			$company_oib = $company->oib;
			
			if($company_oib) {
				//konektiranje na superadmin bazu
				$db_ext = DB::connection('mysql_external'); 

				//dohvaćanje iz tbl zahtjeva zahtjev korisnika
				try {
					$client_request = $db_ext->table('client_requests')->join('clients','client_requests.client_id','clients.id')->select('client_requests.no_users','clients.*')->where('clients.oib',$company_oib)->orderBy('client_requests.updated_at','DESC')->first();
					if($client_request) {
						$users_number = $client_request->no_users;					
					}
				} catch (Exception $e) {
					session()->flash('error',  __('ctrl.retrieving_error'));
					return redirect()->back();
					//echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
			} else {
				session()->flash('error',  __('ctrl.no_oib'));
				return redirect()->back();
			}
		} else {
			session()->flash('error',  __('ctrl.no_company'));
			return redirect()->back();
		}
	
		return $users_number;

	}

	public function structure ( ) 
	{
		$company = Company::with('hasDepartments_level0')->with('hasDepartments_level1')->with('hasDepartments_level2')->first();

		return view('Centaur::companies.structure',['company' => $company]);
	}
}