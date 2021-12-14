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
        if(isset($_GET["table"]) && isset($_GET["id"])) {
            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));
            $object = ORM::for_table($_GET["table"])->find_one($_GET["id"]);
            if($object) {
                //check for users using the group on group deletion
                if($_GET["table"] == "app_groups") {
                    $sql = "SELECT COUNT(id) FROM users WHERE appGroup LIKE '$object->groupName'";
                    $erg = ORM::for_table('users')->where('appGroup', $object->groupName)->count();
                    if($erg > 0) {
                        echo "ERROR: cannot delete group. There are users in this group.";
                    } elseif($erg == 0) {
                        // $object->delete();
                        echo "success";
                    }                   
                } elseif($_GET["table"] == "users") {
                    $object->delete();
                    echo "success";
                }                
            }
            else {
                echo "ERROR: Object with id ".$_GET["id"]." not found!";
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