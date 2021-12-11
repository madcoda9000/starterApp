<?php
    // required headers
    header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // files needed to connect to database
    // include_once 'config/database.php';
    include_once 'objects/user.php';
    include_once 'vendor/autoload.php';
    include_once 'config/core.php';
    use \Firebase\JWT\JWT;
    
    // instantiate user object
    $user = new User();
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // set user property values
    $user->email = $data->email;
    $email_exists = $user->emailExists();    
    
    // check if email exists and if password is correct
    if($email_exists && password_verify($data->password, $user->password)){
    
        $token = array(
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "iss" => $issuer,
            "data" => array(
                "id" => $user->id,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "email" => $user->email,
                "totp_secret" => $user->totp_secret,
                "totp_enabled" => $user->totp_enabled
            )
        );

        // check if mfa is enabled
        $mfa = 0;
        if($user->totp_enabled == "1") {
            $mfa = 1;
        }
    
        // set response code
        http_response_code(200);
    
        // generate jwt
        $jwt = JWT::encode($token, $key);
        echo json_encode(
                array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "mfa" => $mfa
                )
            );
    
    }
    
    // login failed
    else{
    
        // set response code
        http_response_code(401);
    
        // tell the user login failed
        echo json_encode(array("message" => "Login failed."));
    }
?>