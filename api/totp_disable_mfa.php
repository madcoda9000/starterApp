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
 use RobThree\Auth\TwoFactorAuth; 
 
 // instantiate user object
 $user = new User();
 
 // get jwt
 $jwt=$_POST['jwt'];

 if($jwt){
    try {

        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        $user->id = $decoded->data->id;
        $user->totp_secret = "";
        $user->totp_enabled = 0;

        
            // update user record
            if($user->update_totp()){

                // set response code
                http_response_code(200);
                
                // response in json format
                echo "true";
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