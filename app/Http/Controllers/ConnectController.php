<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDO;

use Centaur\AuthManager;

class ConnectController extends Controller
{
    /** @var Centaur\AuthManager */
    protected $authManager;
    private $connect;
    private $db;
    private $db_table = "users";
    
    /**
   * Set middleware to quard controller.
   *
   * @return void
   */
    public function __construct(/* AuthManager $authManager */)
    { 
     /*    $this->authManager = $authManager; */
        
        $servername = "administracija.duplico.hr";
        $username = "duplicoh_jelena";
        $password = "Sifra123jj";
        $db = 'duplicoh_proizvodnja';
       
        $this->connect = new PDO("mysql:host=$servername;dbname=".$db, $username, $password);
        $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
  /*  
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('Centaur::android.index');
    }
    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }
    public function edit($id)
    { 
        //
    }

    public function update(Request $request, $id)
    {   
        //
    }

    public function destroy($id)
    {
        //
    }
    public function getDb () {
        return $this->connect;
    }

    public function isLoginExist ($email, $password) {
       /*  $query = "select * from ".$this->db_table." where email = '$email' AND password = '$password' Limit 1"; */
        /*  $query = "select * from users where email = 'admin_scheduler@admin.com' Limit 1";
        $result = mysqli_query($this->connect, $query); */


        $result = $this->connect->prepare("SELECT * FROM users where email = 'admin_scheduler@admin.com' Limit 1");
        $result->execute();

        if($result->fetchColumn()){
                
           /*  mysqli_close($this->db->getDb()); */
            $this->db = null;

            return true;
        }
       /*  mysqli_close($this->getDb()); */
        $this->connect = null;
        return false;
    }

    public function isEmailUsernameExist($email){
            
        /* $query = "select * from ".$this->db_table." where email = '$email'";
        $result = mysqli_query($this->getDb(), $query); */
        $result = $this->connect->prepare("SELECT * FROM users where email = 'admin_scheduler@admin.com' Limit 1");
        $result->execute();

        if($result->fetchColumn()){
            
           /*  mysqli_close($this->getDb()); */
           $this->connect = null;
            return true;
        }
        return false;
    }

    public function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function createNewRegisterUser($password, $email){
              
        $isExisting = $this->isEmailUsernameExist($email);
        
        if($isExisting){
            
            $json['success'] = 0;
            $json['message'] = "Error in registering. Probably the user/email already exists";
        }
        
        else{
            
        $isValid = $this->isValidEmail($email);
            
            if($isValid)
            {
            $query = "insert into ".$this->db_table." (password, email, created_at, updated_at) values ('$password', '$email', NOW(), NOW())";
            
            $inserted = mysqli_query($this->db->getDb(), $query);
            
            if($inserted == 1){
                
                $json['success'] = 1;
                $json['message'] = "Successfully registered the user";
                
            }else{
                
                $json['success'] = 0;
                $json['message'] = "Error in registering. Probably the user/email already exists";
                
            }
            
            /* mysqli_close($this->db->getDb()); */
            $this->connect = null;
            }
            else{
                $json['success'] = 0;
                $json['message'] = "Error in registering. Email Address is not valid";
            }
            
        }
        
        return $json;
        
    }
    
    public function loginUsers($username, $password){
        
        $json = array();
        
        $canUserLogin = $this->isLoginExist($username, $password);
     
        if($canUserLogin){
            
            $json['success'] = 1;
            $json['message'] = "Successfully logged in";
            
        }else{
            $json['success'] = 0;
            $json['message'] = "Incorrect details";
        }
        return $json;
    }
}
