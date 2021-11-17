<?php
 //required headers
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Methods: POST");
 header("Access-Control-Max-Age: 3600");
 header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 // required to encode json web token
 include_once 'config/core.php';
 include_once 'vendor/autoload.php';

 use \Firebase\JWT\JWT;
 use RobThree\Auth\TwoFactorAuth;
 
 //files needed to connect to database
 include_once 'config/database.php';
 include_once 'objects/user.php';

 
 // get jwt
 $jwt=$_POST['jwt'];

 if($jwt){
    try {

        $tfa = new TwoFactorAuth();
        $secret = $tfa->createSecret();
        $qr = $tfa->getQRCodeImageAsDataUri('shPASS', $secret);

        // set response code
        http_response_code(200);

        // response
        $resp = "Please scan this qr code in your totp app..<br><img src='" . $qr . "'><br><br>Or, enter this code in your totp app..<br><br><span id='totpsecret'>" . chunk_split($secret, 4, ' ') . "</span>";
        $resp = $resp . "<br><br>When you've scanned the qr code successfully, please click next step..";
        echo $resp;
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