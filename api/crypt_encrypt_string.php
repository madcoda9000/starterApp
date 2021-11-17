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


 use \Firebase\JWT\JWT;
 use RobThree\Auth\TwoFactorAuth;
 
 //files needed to connect to database
 include_once 'config/database.php';
 include_once 'objects/user.php';
 

  // get jwt
  $jwt=$_POST['jwt'];

  if($jwt){
     try {

      if( isset($_POST['value']) ) {
         // need to be Encrypted
         $simple_string = $_POST['value'];
         
         // Store the cipher method
         $ciphering = "AES-128-CTR";
         
         // Use OpenSSl Encryption method
         $iv_length = openssl_cipher_iv_length($ciphering);
         $options = 0;
         
         // Non-NULL Initialization Vector for encryption
         $encryption_iv = '1234567891011121';
         
         // Store the encryption key
         $encryption_key = $key;
         
         // Use openssl_encrypt() function to encrypt the data
         $encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);

         // retunr the encrypted string
         echo $encryption;
      }
      else {
         throw new Exception('no value to encrypt!');
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