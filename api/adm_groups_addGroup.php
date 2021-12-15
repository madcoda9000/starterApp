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
        if(isset($_GET["gName"])) {
            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));
            $group = ORM::for_table('app_groups')->create();
            $group->groupName = $_GET["gName"];
            $group->save();
            echo "success";
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