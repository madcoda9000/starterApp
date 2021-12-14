<?php
 //required headers
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Methods: POST");
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

 $jwt=$_POST['jwt'];

 if($jwt){
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $find_user_by_mail = ORM::for_table('users')->where('email', $decoded->data->email)->find_one();
        if($find_user_by_mail){
            if($find_user_by_mail->appGroup == "admins") {
                echo "true";
            } else {
                echo "false";
            }
        } else {
            echo "false";
        }
    } 
    // show error
    catch (Exception $e){
        
        // set response code
        http_response_code(400);
    
        // show error message
        echo $e->getMessage();
    }
 }
 // show error message if jwt is empty
 else{
    
    // set response code
    http_response_code(401);

    // tell the user access denied
    echo "Access denied.";
 }
?>