<?php

namespace App\Http\Controllers;
use App\Models\VacationRequest;
use phpxmlrpc\lib\xmlrpc;
use Illuminate\Http\Request;
use xmlrpc_client;
use xmlrpcmsg;
use xmlrpcval;

class ApiController extends Controller
{
    private $user;
    private $password;
    private $dbname;
    private $server_url;
    private $vacationRequest;
    private $api;

    /**
     *
     * Set middleware to quard controller.
     * @return void
     */
    public function __construct()
    {
        include('../phpxmlrpc-4.4.1/lib/xmlrpc.inc');
       

        $this->middleware('sentinel.auth');
        $this->user = 'employee_portal_admin';
        $this->password = 'duplico1234';
        $this->dbname = 'duplico_test';
        $this->server_url = 'https://test.odoo.eur.hr:8016/xmlrpc/';
        $this->API = 'employee.portal.api';
     
    }
    
    public function index()
    {
        /* $response = $this->connect_id_get(); */
         $response = $this->get_available_leave_types();
         /*    array:4 [▼
                "holiday" => "Godi?nji odmor"
                2 => "Bolovanje"
                3 => "Kompenzacijska naknada dana"
                4 => "Nepla?eno"
            ] */
        /*  $response = $this->get_employee_available_projects(33);  */
           /*  array:1 [▼
                58 => "[P-000] 000 Implementacija Odoo ERP-a, [0001] Duplico d.o.o."
            ] */
        /* $response = $this->send_leave_request(); */

       /*  $response = $this->get_employee_project_tasks(33); */

        return view('Centaur::api_erp.index',['response' => $response]);
    }

    function connect_id_get() 
    {
        $user = $this->user;
        $password =  $this->password;
        $dbname = $this->dbname;
        $server_url = $this->server_url;
    
        if(isset($_COOKIE["user_id"]) == true){
            if($_COOKIE["user_id"]>0){
                return $_COOKIE["user_id"];
            }
        }
        
        $sock = new xmlrpc_client($server_url."common");
        $sock->setSSLVerifyPeer(0);
        $msg = new xmlrpcmsg('login');
        $msg->addParam(new xmlrpcval($dbname, "string"));
        $msg->addParam(new xmlrpcval($user, "string"));
        $msg->addParam(new xmlrpcval($password, "string"));
        $resp = $sock->send($msg);

        $val = $resp->value();
        if(! is_int($val)){
            $id = $val->scalarval();
            setcookie("user_id", $id, time() + 3600);
            if($id > 0){
                return $id;
            } 
            else{
                return -1;
            }
        } 
    }
  
    public function get_available_leave_types()
    {
        $GLOBALS['xmlrpc_internalencoding']='UTF-8';
        $user = $this->user;
        $password = $this->password ;
        $dbname = $this->dbname;
        $server_url = $this->server_url;

        if(session()->exists('xmlr')) {
            $id = intval(session('xmlr'));
        } else {
            $id = $this->connect_id_get();
        }
        
        $sock = new xmlrpc_client($server_url."object");
        $sock->setSSLVerifyPeer(0);
        $API = $this->API;
        $get_available_leave_types = new xmlrpcmsg('execute');
        $get_available_leave_types->addParam(new xmlrpcval($dbname, "string"));
        $get_available_leave_types->addParam(new xmlrpcval($id, "int"));
        $get_available_leave_types->addParam(new xmlrpcval($password, "string"));
        $get_available_leave_types->addParam(new xmlrpcval($API, "string"));
        $get_available_leave_types->addParam(new xmlrpcval("get_available_leave_types", "string"));
       
        $resp =  $sock->send($get_available_leave_types);
       
        $val = $resp->value();
       
        $ids = $val->scalarval();
        $types_array = array();
        foreach ($ids as $type) {
            if(isset($type->me['struct']['id']->me['int'])) {
                $type_id = $type->me['struct']['id']->me['int'];
            } else {
                $type_id = $type->me['struct']['id']->me['string'];
            }
            $types_array[$type_id] = $type->me['struct']['value']->me['string'];
        }
      
        /*   array:4 [▼
            "holiday" => "Godi?nji odmor"
            2 => "Bolovanje"
            3 => "Kompenzacijska naknada dana"
            4 => "Nepla?eno"
        ] */
        return $types_array;
    }

    public function get_employee_available_projects($employee_id)
    {
        $user = $this->user;
        $password = $this->password ;
        $dbname = $this->dbname;
        $server_url = $this->server_url;

        if(session()->exists('xmlr')) {
            $id = intval(session('xmlr'));
        } else {
            $id = $this->connect_id_get();
        }
 
        $sock = new xmlrpc_client($server_url."object");
        $sock->setSSLVerifyPeer(0);
        $API = $this->API;

        $get_employee_available_projects = new xmlrpcmsg('execute');
        $get_employee_available_projects->addParam(new xmlrpcval($dbname, "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($id, "int"));
        $get_employee_available_projects->addParam(new xmlrpcval($password, "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($API, "string"));
        $get_employee_available_projects->addParam(new xmlrpcval("get_employee_available_projects", "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($employee_id, "int"));
        $resp = $sock->send($get_employee_available_projects);
      
        $val = $resp->value();
    
        $ids = $val->scalarval();
        $projects = array();
     
        foreach ($ids as $id) {
            $projects[$id->me['struct']['id']->me['int']] = $id->me['struct']['name']->me['string'];
           /*  array_push($projects, $id->me['struct']['name']->me['string']); */
        }

            /*  array:1 [▼
                58 => "[P-000] 000 Implementacija Odoo ERP-a, [0001] Duplico d.o.o."
            ] */
        return $projects;
    }

    public function get_employee_project_tasks($employee_id)
    {
        $user = $this->user;
        $password = $this->password ;
        $dbname = $this->dbname;
        $server_url = $this->server_url;
       
        if(session()->exists('xmlr')) {
            $id = intval(session('xmlr'));
        } else {
            $id = $this->connect_id_get();
        }
        $projects = $this->get_employee_available_projects($employee_id);
        // id prvog projekta
        if ( $projects ) {
            $project_id = array_keys($projects)[0];
        } else  {
            $project_id = null;
        }
       
        $sock = new xmlrpc_client($server_url."object");
        $sock->setSSLVerifyPeer(0);

        $API = $this->API;

        $get_available_project_tasks = new xmlrpcmsg('execute');
        $get_available_project_tasks->addParam(new xmlrpcval($dbname, "string"));
        $get_available_project_tasks->addParam(new xmlrpcval($id, "int"));
        $get_available_project_tasks->addParam(new xmlrpcval($password, "string"));
        $get_available_project_tasks->addParam(new xmlrpcval($API, "string"));
        $get_available_project_tasks->addParam(new xmlrpcval("get_available_project_tasks", "string"));
        $get_available_project_tasks->addParam(new xmlrpcval($employee_id, "int"));
        $get_available_project_tasks->addParam(new xmlrpcval($project_id, "int"));
       
        $resp = $sock->send($get_available_project_tasks);
        
        $val = $resp->value();
     
        $ids = $val->scalarval();
        $tasks = array();
     
        foreach ($ids as $id) {
           /*  dd($id); */
            $tasks[$id->me['struct']['id']->me['int']] = $id->me['struct']['name']->me['string'];
           /*   array_push($tasks, $id->me['struct']['name']->me['string']); */ 
        }

        return $tasks;
    }

    function send_leave_request( $absence ) 
    {
        $user = $this->user;
        $password = $this->password ;
        $dbname = $this->dbname;
        $server_url = $this->server_url;

        if(session()->exists('xmlr')) {
            $uid = intval(session('xmlr'));
        } else {
            $uid = $this->connect_id_get();
        }
        $sock = new xmlrpc_client($server_url."object");
        $sock->setSSLVerifyPeer(0);
        /* $sock->setDebug(1); */
        $param = array();
        $API = $this->API;

        $employee_id = intval( $absence->employee->erp_id); 
        $leave_type_id = $absence->ERP_leave_type;

        if(gettype($leave_type_id) == "integer") {
            $type_id = "int";
        } else {
            $type_id = "string";
        }
        $task_id = intval($absence->erp_task_id);
        $date_from = $absence->start_date;
        $date_to = $absence->end_date;
        if( $absence->absence->mark == 'IZL' ) {
            $date_from = $date_from . ' ' . $absence->start_time;
            $date_to = $date_to . ' ' . $absence->end_time;
        } 

        $note = $absence->comment;

        // piše su uvijek na isti projekt Interno !!!!!!!!!!!!!!!!!!!!!!!  
        $get_available_projects = $this->get_employee_available_projects( $employee_id );
        $project_id = key($get_available_projects);

        $param['dbname'] = $dbname;
        $param['uid'] = $uid;
        $param['password'] = $password;
        $param['API'] = $API;
        $param['method'] = 'vacation_request_create';
        $param['employee_id'] = $employee_id;
        $param['leave_type_id'] = $leave_type_id;
        $param['task_id'] = $task_id;
        $param['date_from'] = $date_from;
        $param['date_to'] = $date_to;
        $param['note'] = $note;

        $get_employee_available_projects = new xmlrpcmsg('execute');
        $get_employee_available_projects->addParam(new xmlrpcval($dbname, "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($uid, "int"));
        $get_employee_available_projects->addParam(new xmlrpcval($password, "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($API, "string"));
        $get_employee_available_projects->addParam(new xmlrpcval("vacation_request_create", "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($employee_id, "int"));
        $get_employee_available_projects->addParam(new xmlrpcval($task_id, "int"));
        $get_employee_available_projects->addParam(new xmlrpcval($leave_type_id, $type_id));
        $get_employee_available_projects->addParam(new xmlrpcval($date_from, "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($date_to, "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($note, "string"));
        
        $resp = $sock->send($get_employee_available_projects);
        
        $val = $resp->value();
       
       // $ids = $val->scalarval();

        return $val;
    }

      /**
         * $client = xml-rpc handler
         * $relation = name of the relation ex: res.partner
         * $attribute = name of the attribute ex:code
         * $operator = search term operator ex: ilike, =, !=
         * $key=search for
    */
    function search($client,$relation,$attribute,$operator,$keys) {
        $user = $this->user;
        $password =  $this->password;
        $dbname = $this->dbname;
        $server_url = $this->server_url;
        $userId = -1;

        $key = array(new xmlrpcval(array(new xmlrpcval($attribute , "string"),
                new xmlrpcval($operator,"string"),
                new xmlrpcval($keys,"string")),"array"),
            );

        if($userId<=0) {
            $this->connect_id_get();
        }

        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($dbname, "string"));
        $msg->addParam(new xmlrpcval($userId, "int"));
        $msg->addParam(new xmlrpcval($password, "string"));
        $msg->addParam(new xmlrpcval($relation, "string"));
        $msg->addParam(new xmlrpcval("search", "string"));
        $msg->addParam(new xmlrpcval($key, "array"));

        $resp = $client->send($msg);
        $val = $resp->value();
        $ids = $val->scalarval();

        return $ids;
    }

    /**
         * $client = xml-rpc handler
         * $relation = name of the relation ex: res.partner
         * $attribute = name of the attribute ex:code
         * $operator = search term operator ex: ilike, =, !=
         * $id = id of the record to be updated
         * $data = data to be updated
     */
    function write($client,$relation,$attribute,$operator,$data,$id) {
        
        $user = $this->user;
        $password =  $this->password;
        $dbname = $this->dbname;
        $server_url = $this->server_url;
        $userId = -1;
       

        $id_val = array();
        $id_val[0] = new xmlrpcval($id, "int");

        if($userId<=0) {
            $this->connect_id_get();
        }

        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($dbname, "string"));
        $msg->addParam(new xmlrpcval($userId, "int"));
        $msg->addParam(new xmlrpcval($password, "string"));
        $msg->addParam(new xmlrpcval($relation, "string"));
        $msg->addParam(new xmlrpcval("write", "string"));
        $msg->addParam(new xmlrpcval($id, "array"));
        $msg->addParam(new xmlrpcval($data, "struct"));

        $resp = $client->send($msg);
        $val = $resp->value();
        $record = $val->scalarval();

        return $record;
    }

}