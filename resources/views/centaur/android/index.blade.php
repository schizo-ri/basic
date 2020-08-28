@php    
    use Illuminate\Support\Facades\Hash;

    $response = array();
   
    $password = null;
    $email = null;
    
    /* $password = "jjuras226735";
    $email = "admin_scheduler@admin.com"; */
    
    if(isset($_GET['password'])){
        $password = $_GET['password'];
    } elseif (isset($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        $response['success'] = -1;
        $response['message1'] = "Nema passworda ili emaila";
    }
    
    if(isset($_GET['email'])){
        $email = $_GET['email'];
    } elseif (isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $response['success'] = -1;
        $response['message2'] = "Nema passworda ili emaila";
    }
    
    // Login
    if($password && $email){
        $hashed_password = Hash::make($password);
        $response = loginUsers($email, $hashed_password);
    } else {
        $response['success'] = -1;
        $response['message3'] = "Nema passworda ili emaila";
    }

    echo json_encode($response);

    function loginUsers($email, $password){
        
        $json = array();
        
        $canUserLogin = isLoginExist($email, $password);
     
        if($canUserLogin){
            
            $json['success'] = 1;
            $json['message'] = "Successfully logged in";
            
        }else{
            $json['success'] = 0;
            $json['message'] = "Incorrect details";
        }
        return $json;
    }
    
    function isLoginExist ($email, $password) {
        $servername = "administracija.duplico.hr";
        $username = "duplicoh_jelena";
        $password = "Sifra123jj";
        $db = 'duplicoh_proizvodnja';
        $conn = mysqli_connect($servername, $username, $password, $db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT * FROM users where email = 'admin_scheduler@admin.com' Limit 1";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            mysqli_close($conn);
            return false;
        }

        mysqli_close($conn);
        return false;
    }

@endphp
