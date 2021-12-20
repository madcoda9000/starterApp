<?php
//required headers
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Methods: GET");
 header("Access-Control-Max-Age: 3600");
 header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 // required to encode json web token
 include_once 'config/core.php';
 include_once 'vendor/autoload.php';
 include_once 'objects/user.php';

 use \Firebase\JWT\JWT;
  // create orm instance
  ORM::configure('mysql:host=' . $DB_host . ';dbname='.$DB_name);
  ORM::configure('username', $DB_user);
  ORM::configure('password', $DB_pass);
  ORM::configure('return_result_sets', true);

 $jwt=$_GET['jwt'];

 if($jwt){
    try {
        if(isset($_GET["uID"]) && isset($_GET['action'])) {
            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));
            $user = ORM::for_table('users')->find_one($_GET['uID']);
            if($user) {
                if($_GET['action']=='disable') {
                    $user->accState=0;
                    $user->save();
                    echo "success";
                } elseif($_GET['action']=='enable') {
                    $user->accState=1;
                    $user->failedLogonCount=0;
                    $user->save();
                    echo "success";
                }                
            } else {
                echo "Error: user with id ".$_GET['uID']." not found!";
            }
        }
        else {
            echo "ERROR: Missing Parameters.";
        }
    } 
    // show error
    catch (Exception $e){
    
        // show error message
        echo $e->getMessage();
    }
 }
 // show error message if jwt is empty
 else{
    
    // tell the user access denied
    echo "Access denied.";
 }
?>