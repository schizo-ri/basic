<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDO;
use Illuminate\Support\Facades\Hash;
use Centaur\AuthManager;
use Mysqli;

class ConnectController extends Controller
{
    /** @var Centaur\AuthManager */
    protected $authManager;
    private $connect;
    private $db;
    private $db_table = "users";
    private $servername;
    private $username;
    private $password;

    /**
   * Set middleware to quard controller.
   *
   * @return void
   */
    public function __construct(AuthManager $authManager)
    { 
        $this->sentinel = app()->make('sentinel');
        $this->authManager = $authManager;
        $this->servername = "127.0.0.1";
       /*  $this->username = "root";
        $this->password = ""; */
        $this->username = "myintran";
         $this->password = "2UhAghyLBRil";
        $this->db = 'myintran_duplico';
       
        $this->connect = new PDO("mysql:host=$this->servername;dbname=".$this->db, $this->username, $this->password);
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
        $response = array();
        
        $password = null;
        $email = null;
        /* $password = "jjuras226735"; */
        /* $email = "admin_portal@admin.com"; */
           if(isset($_GET['password'])){
                $password = $_GET['password'];
            } elseif (isset($_POST['password'])) {
                $password = $_POST['password'];
            } else {
                $response['success'] = -1;
                $response['message1'] = "Nema passworda";
            }
            
            if(isset($_GET['email'])){
                $email = $_GET['email'];
            } elseif (isset($_POST['email'])) {
                $email = $_POST['email'];
            } else {
                $response['success'] = -1;
                $response['message2'] = "Nema emaila";
            }
       
        // Login
        if($password && $email){
            $response = $this->loginUsers($email, $password);
        } else {
            $response['success'] = -1;
            $response['message3'] = 'Neuspjeli login';
        }

        return view('Centaur::android.index',['response' => json_encode($response)]);
    }

    
    public function loginUsers($email, $password){
        $json = array();
        
        $canUserLogin = $this->isLoginExist($email, $password);
       
        if($canUserLogin){
            
            $json['success'] = 1;
            $json['message'] = "Successfully logged in";
            
        } else {
            $json['success'] = 0;
            $json['message'] = "Incorrect details";
        }
        return $json;
    }

    public function isLoginExist ($email, $password) 
    {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->db);
        mysqli_set_charset($conn ,"utf-8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $credentials = [
            'email' => trim($email),
            'password' =>  $password,
        ];

        $user = $this->sentinel->authenticate($credentials, false); 
       
        $conn->close();

        if( $user ) {
            return true;
        } else {
            return false;
        }
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
}
