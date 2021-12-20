<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // files needed to connect to database
    // include_once 'config/database.php';
    include_once 'objects/user.php';
    include_once 'objects/mail.php';
    include_once 'vendor/autoload.php';
    include_once 'config/core.php';
    use \Firebase\JWT\JWT;
    // create orm instance
    ORM::configure('mysql:host=' . $DB_host . ';dbname='.$DB_name);
    ORM::configure('username', $DB_user);
    ORM::configure('password', $DB_pass);
    
    // instantiate user object
    $user = new User();
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // set user property values
    $user->email = $data->email;
    $email_exists = $user->emailExists();    
    $passVerify = password_verify($data->password, $user->password);

    try {
    
    if ($email_exists == false) {
        // set response code
        http_response_code(401);
        
        // tell the user login failed because user was not found
        echo json_encode(array("message" => "User not found."));  
    } 

    // check if user is disabled already
    elseif($email_exists == true && $user->accState==0) {
        // set response code
        http_response_code(401);
        
        // tell the user login failed because user was not found
        echo json_encode(array("message" => "Too many failed logins. Your account is disabled! Please contact your adminstrator")); 
    }

    // check if failed logon count is exceeded
    elseif($email_exists == true && $user->failedLogonCount >= $APP_failedLogonCount && $user->accState==1) {
        // we have to disable this user
        $edit_user = ORM::for_table('users')->find_one($user->id);
        $edit_user->accState = 0;
        $edit_user->save();

        // check if we have to notfy the administrator
        if($APP_lockOutMail==true) {
            $m = new mail($MAIL_Server, $MAIL_Port, $MAIL_User, $MAIL_Pass, $MAIL_Encryption, $APP_admin_from_address, $APP_admin_to_address, $MAIL_useSmtpAuth);
            $m->t_subject = "Failed logon attempts exceeded..";
            $m->t_body = "WARNING: The user account ". $edit_user->email . " has been disabled due too many failed logons.";
            $res = $m->sendAdminMail();
            if($res!="success") {
                // set response code
                http_response_code(401);
                
                // tell the user login failed because password was wrong
                echo json_encode(array("message" => "Too many failed logins. Your account is disabled! Your administrator could not be notified! Please contact your administrator.<br><br>Error: ".$res));
            } else {
                // set response code
                http_response_code(401);
                
                // tell the user login failed because password was wrong
                echo json_encode(array("message" => "Too many failed logins. Your account is disabled! Your administrator was informed about that."));
            }
        }        
    } 

    // on wrong login credentials update failed logon count
    elseif($email_exists == true && $passVerify == false) {
        $edit_user = ORM::for_table('users')->find_one($user->id);
        if($edit_user) {
            $edit_user->failedLogonCount = $edit_user->failedLogonCount + 1;
            $edit_user->save();
            // set response code
            http_response_code(401);
        
            // tell the user login failed because password was wrong
            echo json_encode(array("message" => "Wrong password!"));
        } else {
            // set response code
            http_response_code(401);
            
            // tell the user login failed because user was not found
            echo json_encode(array("message" => "User not found."));
        } 
    } 
    
    // check if email exists and if password is correct
    elseif($email_exists && password_verify($data->password, $user->password)){

        // if login was successful reset failed login count
        $user->resetFailedLogonCount();
    
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
                "totp_enabled" => $user->totp_enabled,
                "accState" => $user->accState
            )
        );

        // check if mfa is enabled
        $mfa = 0;
        if($user->totp_enabled == "1") {
            $mfa = 1;
        }

        // check if user is disabled
        $accState = 0;
        if($user->accState==1) {
            $accState = 1;
        }
    
        // set response code
        http_response_code(200);
    
        // generate jwt
        $jwt = JWT::encode($token, $key);
        echo json_encode(
                array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "mfa" => $mfa,
                    "accState" => $accState
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
}
catch (Exception $e) {
    // set response code
    http_response_code(401);
    
    // tell the user login failed
    echo json_encode(array("message" => $e->getMessage()));
}
?>