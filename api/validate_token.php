<?php
    // required headers
    header("Access-Control-Allow-Origin: http://localhost/shpass111/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // required to decode jwt
    include_once 'config/core.php';
    include_once 'vendor/autoload.php';
    
    use \Firebase\JWT\JWT;
    // create orm instance
    ORM::configure('mysql:host=' . $DB_host . ';dbname='.$DB_name);
    ORM::configure('username', $DB_user);
    ORM::configure('password', $DB_pass);
    ORM::configure('return_result_sets', true);
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // get jwt
    $jwt=isset($data->jwt) ? $data->jwt : "";
    
    // if jwt is not empty
    if($jwt){
    
        // if decode succeed, show user details
        try {
            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));
            $uID = $decoded->data->id;            

            // check if user is disabled in the meantime
            $user = ORM::for_table('users')->find_one($uID);
            if($user->accState==0) {
                // set response code
                http_response_code(401);
            
                // tell the user access denied  & show error message
                echo json_encode(array(
                    "message" => "User account deactivated!",
                    "error" => $e->getMessage()
                ));
            } else {
                // set response code
                http_response_code(200);
        
                // show user details
                echo json_encode(array(
                    "message" => "Access granted.",
                    "data" => $decoded->data
                ));
            }    
        }
    
        // if decode fails, it means jwt is invalid
        catch (Exception $e){
        
            // set response code
            http_response_code(401);
        
            // tell the user access denied  & show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    }
    
    // show error message if jwt is empty
    else{
    
        // set response code
        http_response_code(401);
    
        // tell the user access denied
        echo json_encode(array("message" => "Access denied."));
    }
?>