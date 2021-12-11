<?php
 //required headers
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Methods: POST");
 header("Access-Control-Max-Age: 3600");
 header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 // required to encode json web token
 include_once 'config/core.php';
 include_once 'vendor/autoload.php';
 include_once 'objects/Openssl_EncryptDecrypt.php';
 include_once 'objects/user.php';

 use \Firebase\JWT\JWT;
 use RobThree\Auth\TwoFactorAuth;
 
// get jwt
 $jwt=$_POST['jwt'];

 if($jwt){
    try {
        // check what to do
        if(isset($_POST['method'])) {
            $method = $_POST['method'];
            if($method == 'activateMFA') {
                if( isset($_POST['totpcode']) && isset($_POST['totpsecret'])) {

                    $user_tok = $_POST['totpcode'];
                    $user_secret = $_POST['totpsecret'];
        
                    // get the encrypted string
                    $encryption = $user_secret;            
        
                    // Store the cipher method
                    $ciphering = "AES-128-CTR";
        
                    // Use OpenSSl Encryption method
                    $iv_length = openssl_cipher_iv_length($ciphering);
                    $options = 0;
        
                    // Non-NULL Initialization Vector for decryption
                    $decryption_iv = '1234567891011121';
                    
                    // Store the decryption key
                    $decryption_key = $key;
                    
                    // Use openssl_decrypt() function to decrypt the data
                    $decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv);
        
                    $tfa = new TwoFactorAuth();
                    if($tfa->verifyCode(str_replace(" ", "", $decryption), $user_tok) === true) {
        
                        // set response code
                        http_response_code(200);
                
                        // show user details
                        echo 'true';
        
                    } else {
                        echo 'false';
                    }
                } 
                else {
                    throw new Exception('no value to validate toke!');
                }
            } else if($method == 'loginmfa') {
                // decode jwt
                $decoded = JWT::decode($jwt, $key, array('HS256'));

                if( isset($_POST['totpcode'])) {

                    $user_tok = $_POST['totpcode'];
                    $user_secret = $decoded->data->totp_secret;
        
                    // get the encrypted string
                    $encryption = $user_secret;            
        
                    // Store the cipher method
                    $ciphering = "AES-128-CTR";
        
                    // Use OpenSSl Encryption method
                    $iv_length = openssl_cipher_iv_length($ciphering);
                    $options = 0;
        
                    // Non-NULL Initialization Vector for decryption
                    $decryption_iv = '1234567891011121';
                    
                    // Store the decryption key
                    $decryption_key = $key;
                    
                    // Use openssl_decrypt() function to decrypt the data
                    $decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv);
        
                    $tfa = new TwoFactorAuth();
                    if($tfa->verifyCode(str_replace(" ", "", $decryption), $user_tok) === true) {
        
                        // set response code
                        http_response_code(200);
                
                        // show user details
                        echo 'true';
        
                    } else {
                        echo 'false';
                    }
                } 
                else {
                    throw new Exception('no value to validate toke!');
                }
            }
        } else {
            throw new Exception('no method provided!');
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