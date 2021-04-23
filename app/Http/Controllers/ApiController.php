<?php

namespace App\Http\Controllers;
use phpxmlrpc\lib\xmlrpc;
use Illuminate\Http\Request;
use PhpXmlRpc\Client as xmlrpc_client;
use PhpXmlRpc\Request as xmlrpcmsg;
use PhpXmlRpc\Value as xmlrpcval;
use App\Models\Employee;
use App\Models\Afterhour;
use App\Models\Absence;
use Log;
use DateTime;
use DateInterval;
use DatePeriod;

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
        if($_SERVER['DOCUMENT_ROOT'] == '') {
            include_once('duplico.myintranet.io/phpxmlrpc-4.4.1/lib/xmlrpc.inc');
        } else {
            include_once('phpxmlrpc-4.4.1/lib/xmlrpc.inc');
        }

        $this->middleware('sentinel.auth');
        $this->user = 'employee_portal_admin';
        $this->password = 'duplico1234';
        /* $this->dbname = 'duplico_test';
        $this->server_url = 'https://test.odoo.eur.hr:8016/xmlrpc/'; */
        $this->dbname = 'duplico';
        $this->server_url = 'https://sv019erp.intranet.duplico.hr:8025/xmlrpc/';
        $this->API = 'employee.portal.api';
    }
    
    public function index()
    {
        /* $response = $this->connect_id_get(); */

        /*  $response = $this->get_available_leave_types(); */

        /*  array:9 [▼
            "holiday" => "Godišnji odmor"
            2 => "Bolovanje"
            3 => "Kompenzacijska naknada dana"
            4 => "Neplaćeno"
            63 => "Izlazak"
            64 => "Plaćeni dopust"
            65 => "Neplaćeni dopust"
            66 => "Slobodan dan"
            67 => "Prekovremeni sati"
        ]
        */
        /* $response = $this->get_employee_available_projects(45,date('Y-m-d')); */
        
        $response = $this->get_employee_available_projects(45, null);

        /* array:4 [▼
        55 => "[P-002] 002 Interni poslovi Duplico, [0001] Duplico d.o.o."
        328 => "[P-2378] INA Rafinerija Rijeka Urinj - izvođenje svih instalacijskih radova i puštanje u pogon sustava instalacije procesnog grijanja prema stavkama grupe 1650, ▶"
        1348 => "[P-2968] Izvođenje elektroinstalacija na modulima IS PARKLANDS IDC8 (12 modula), [0976] VERTIV CROATIA d.o.o."
        1397 => "[P-2999] Izvođenje elektro radova na modulima Edgeconnex AMS05PH02, [0976] VERTIV CROATIA d.o.o."
      ] */
       
      /*   $response = $this->get_employee_project_tasks(45, date('Y-m-d'), 1348 );  */
        /*  array:2 [▼
            72 => "Kategorizacija artikala za ERP"
            1117 => "Rekapitulacija projekta - proučiti"
        ] */

        
        /* $response = $this->send_leave_requestSick( Absence::find(3884), 'abs' ); */

        /* $response = $this->send_leave_request( Absence::find(3825), 'abs' ); // izostanak */
       /*  $response = $this->send_leave_request( Afterhour::find(3503), 'aft' ); */

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
       /*  $sock->setSSLVerifyPeer(0); */
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
        /* $sock->setSSLVerifyPeer(0); */
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

    public function get_employee_available_projects($erp_id, $date)
    {
        $user = $this->user;
        $password = $this->password;
        $dbname = $this->dbname;
        $server_url = $this->server_url;
        $param = array();

        if(session()->exists('xmlr')) {
            $id = intval(session('xmlr'));
        } else {
            $id = $this->connect_id_get();
        }
 
        $sock = new xmlrpc_client($server_url."object");
        $sock->setSSLVerifyPeer(0);
        $API = $this->API;

        $param['dbname'] = $dbname;
        $param['uid'] = $id;
        $param['password'] = $password;
        $param['API'] = $API;
        $param['method'] = 'get_employee_available_projects';
        $param['employee_id'] = intval($erp_id);
        $get_employee_available_projects = new xmlrpcmsg('execute');
        $get_employee_available_projects->addParam(new xmlrpcval($param['dbname'], "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($param['uid'], "int"));
        $get_employee_available_projects->addParam(new xmlrpcval($param['password'], "string"));
        $get_employee_available_projects->addParam(new xmlrpcval( $param['API'] , "string"));
        $get_employee_available_projects->addParam(new xmlrpcval( $param['method'], "string"));
        $get_employee_available_projects->addParam(new xmlrpcval($param['employee_id'], "int"));
        $get_employee_available_projects->addParam(new xmlrpcval($date, "string"));
        $resp = $sock->send($get_employee_available_projects);

        $val = $resp->value();
    
        $projects = array();

        if(! is_int($val)){
            $ids = $val->scalarval();
            foreach ($ids as $id) {
                $projects[$id->me['struct']['id']->me['int']] = $id->me['struct']['name']->me['string'];
               /*  array_push($projects, $id->me['struct']['name']->me['string']); */
            }
        } 
        /*  array:1 [▼
            58 => "[P-000] 000 Implementacija Odoo ERP-a, [0001] Duplico d.o.o."
        ] */

        return $projects;
    }

    public function get_employee_project_tasks($employee_id, $date, $project )
    {
        if($employee_id && $employee_id != '') {
            $user = $this->user;
            $password = $this->password ;
            $dbname = $this->dbname;
            $server_url = $this->server_url;
            $API = $this->API;

            if(session()->exists('xmlr')) {
                $uid = intval(session('xmlr'));
            } else {
                $uid = $this->connect_id_get();
            }

            $method = 'get_available_project_tasks';
         
            $param['dbname'] = $dbname;
            $param['uid'] = $uid;
            $param['password'] = $password;
            $param['API'] = $API;
            $param['method'] = $method;
            $param['employee_id'] = $employee_id;
            $param['project'] = $project;
            $param['date'] = $date;
            Log::info('***************** API ERP get_employee_project_tasks ***********************');
            Log::info($param);
            $sock = new xmlrpc_client($server_url."object");
            $sock->setSSLVerifyPeer(0);

            $API = $this->API;
            
            $get_available_project_tasks = new xmlrpcmsg('execute');
            $get_available_project_tasks->addParam(new xmlrpcval($param['dbname'], "string"));
            $get_available_project_tasks->addParam(new xmlrpcval($param['uid'], "int"));
            $get_available_project_tasks->addParam(new xmlrpcval($param['password'], "string"));
            $get_available_project_tasks->addParam(new xmlrpcval($param['API'], "string"));
            $get_available_project_tasks->addParam(new xmlrpcval($param['method'], "string"));
            $get_available_project_tasks->addParam(new xmlrpcval($param['employee_id'], "int"));
            $get_available_project_tasks->addParam(new xmlrpcval($param['project'], "int"));
            $get_available_project_tasks->addParam(new xmlrpcval($param['date'], "string"));
            
            $resp = $sock->send($get_available_project_tasks);
            
            $val = $resp->value();
          
            $tasks = array();
            
            $ids = $val->scalarval();
            
            if( count($ids) > 0) {
                foreach ($ids as $id) {
                    $tasks[$id->me['struct']['id']->me['int']] = $id->me['struct']['name']->me['string'];
                }
            }
    
        } else {
            $tasks = null;
        }
       
        return $tasks;
    }

    function send_leave_request( $absence, $abs_type ) 
    {
        Log::info('***************** API ERP send_leave_request ***********************');
        Log::info("type " . $abs_type);
        Log::info($absence);

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
    
        $employee_id = intval( $absence->employee->erp_id ); 
        if( $abs_type == 'correct' ) {
            $leave_type_id = 69;
        } else {
            $leave_type_id = $absence->ERP_leave_type;
        }
        
        if(is_numeric($leave_type_id) ) {
            $type_id = "int";
            $leave_type_id = intval($leave_type_id);
        } else {
            $type_id = "string";
        }
        
        $task_id = $absence->erp_task_id;

        if( $abs_type == 'abs' ) {
            if( ! $task_id ) {
                $task_id = 5007;
            }
            
            if( $absence->absence->mark == 'BOL' ) {
                if( $absence->end_date == null ) {
                    if( strtotime($absence->start_date) >= strtotime(date('Y-m-d')) ) {
                        $date_from = $absence->start_date; 
                        $date_to = $absence->start_date; 
                    } else {
                        $start = new DateTime( date('Y-m-d'));
                        $start->modify('+1 day');
                        $end = new DateTime(date('Y-m-d'));
                        $end->modify('+1 day');
                        $date_from = $start->format('Y-m-d');
                        $date_to = $end->format('Y-m-d');
                    }
                } else {
                    $date_from = date('Y-m-d'); 
                    $date_to = date('Y-m-d');
                }
            } else {
                $date_from = $absence->start_date;
                $date_to = $absence->end_date;
            }
   
            if( $absence->absence->mark == 'IZL' ) {
                $date_from = $date_from . ' ' . $absence->start_time;
                $date_to = $date_to . ' ' . $absence->end_time;
            }  
        } 
        if( $abs_type == 'aft' ) {
            if( ! $task_id ) {
                $task_id = 5157;
            }
            $date_from = $absence->date . ' ' . $absence->start_time;
            $end = new DateTime( $date_from );  
            $period = explode(':', $absence->approve_h );
            $hour =  $period[0];
            $minute =  $period[1];
          
            $end->modify('+'.$hour.'hours');
            $end->modify('+'.$minute.'minutes');
           
            $date_to = date_format($end,'Y-m-d H:i:s');

           /*  $date_to = $absence->date . ' ' . $absence->end_time; */
        }
        if( $abs_type == 'correct' ) {
            $task_id = 5157;

            $date_from = $absence->date . ' ' . '07:00:00';
            $end = new DateTime( $date_from );  
            $period = explode(':', $absence->approve_h );
            $hour =  $period[0];
            $minute =  $period[1];
          
            $end->modify('+'.$hour.'hours');
            $end->modify('+'.$minute.'minutes');
           
            $date_to = date_format($end,'Y-m-d H:i:s');
        }

        /* $note = $absence->comment; */

        $note = '';

        $method = 'vacation_request_create';
         
        $param['dbname'] = $this->dbname;
        $param['uid'] = $uid;
        $param['password'] = $password;
        $param['API'] = $API;
        $param['method'] = $method;
        $param['employee_id'] = $employee_id;        
        $param['task_id'] = $task_id;
        $param['leave_type_id'] = $leave_type_id;
        $param['date_from'] = $date_from;
        $param['date_to'] = $date_to;
        $param['note'] = $note;
       
        Log::info($param);
        
        if( $leave_type_id ) {
            $get_employee_available_projects = new xmlrpcmsg('execute');
            $get_employee_available_projects->addParam(new xmlrpcval($param['dbname'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['uid'], "int"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['password'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['API'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['method'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['employee_id'], "int"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['task_id'], "int"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['leave_type_id'], $type_id));
            $get_employee_available_projects->addParam(new xmlrpcval($param['date_from'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['date_to'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['note'], "string"));
            $resp = $sock->send($get_employee_available_projects);
          /*   dd($resp); */
            $val = $resp->value();
           
            if(! is_int($val)){
                $id = $val->scalarval();
               
                if($id > 0){
                    return $id;
                } 
                else{
                    return -1;
                }
            } 
            Log::info( "response " . $val );
            Log::info('***************** API ERP kraj ***********************');
    
            return $val;
        } else {
            return false;
        }
    }

    //** Bolovanje ako je početni dan zahtjeva manji od današnjeg dana  kreira u erpu Bolovanje za svaki dan  */
    function send_leave_requestSick( $absence, $abs_type ) 
    {
        Log::info('***************** API ERP send_leave_request SickLeave ***********************');
        Log::info("type " . $abs_type);
        Log::info($absence);

        $user = $this->user;
        $server_url = $this->server_url;

        if(session()->exists('xmlr')) {
            $uid = intval(session('xmlr'));
        } else {
            $uid = $this->connect_id_get();
        }
        $sock = new xmlrpc_client($server_url."object");
        $sock->setSSLVerifyPeer(0);
        $param = array();

        $begin = new DateTime($absence->start_date);
        $end = new DateTime('now');
        $end->setTime(0,0,1);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dan ) {
            $date_from = date_format($dan,'Y-m-d');
            $date_to = date_format($dan,'Y-m-d');
            $param['dbname'] = $this->dbname;
            $param['uid'] = $uid;
            $param['password'] = $this->password;
            $param['API'] = $this->API;
            $param['method'] = 'vacation_request_create';
            $param['employee_id'] = intval( $absence->employee->erp_id );        
            $param['task_id'] = $absence->erp_task_id;
            $param['leave_type_id'] = $absence->ERP_leave_type;
            $param['date_from'] = $date_from;
            $param['date_to'] = $date_to;
            $param['note'] = '';
           
            Log::info($param);
            
            $get_employee_available_projects = new xmlrpcmsg('execute');
            $get_employee_available_projects->addParam(new xmlrpcval($param['dbname'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['uid'], "int"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['password'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['API'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['method'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['employee_id'], "int"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['task_id'], "int"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['leave_type_id'], "int"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['date_from'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['date_to'], "string"));
            $get_employee_available_projects->addParam(new xmlrpcval($param['note'], "string"));
            $resp = $sock->send($get_employee_available_projects);
            /*   dd($resp); */
            $val = $resp->value();
            
          
        }
        if(! is_int($val)){
            $id = $val->scalarval();
            
            if($id > 0){
                return $id;
            } 
            else{
                return -1;
            }
        } 
        Log::info( "response " . $val );
        Log::info('***************** API ERP kraj ***********************');

        return $val;
    }
}