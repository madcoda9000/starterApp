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
      
      if( isset($_POST['value']) ) {

         // get the encrypted string
         $encryption = $_POST['value'];

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
         
         // return the decrypted string
         echo $decryption;

      }
      else {
         throw new Exception('no value to decrypt!');
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