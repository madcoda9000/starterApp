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

        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $sec = $decoded->data->totp_secret;

        // decrypt the secret
        // get the encrypted string
        $encryption = $sec;            
        
        // Store the cipher method
        $ciphering = "AES-128-CTR";

        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Non-NULL Initialization Vector for decryption
        $decryption_iv = '1234567891011121';
        
        // Store the decryption key
        $decryption_key = $key;
        
        // Use openssl_decrypt() function to decrypt the data and strip empty chars
        $decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv);
        $totpsecdec = str_replace(" ", "", $decryption);

        // create qr code
        $tfa = new TwoFactorAuth();
        $qr = $tfa->getQRCodeImageAsDataUri('shPASS', $totpsecdec);

        // prepare response
        $resp = "<b>Your secret as QR Code</b><br><br><img src='" . $qr . "'><br><br><b>Your secret as numbers</b><br>" . $decryption;

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