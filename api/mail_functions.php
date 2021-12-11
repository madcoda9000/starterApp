<?php
 //required headers
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Methods: POST");
 header("Access-Control-Max-Age: 3600");
 header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

 include_once 'config/core.php';
 include_once 'vendor/autoload.php';
 include_once 'objects/user.php';

 use \Firebase\JWT\JWT;
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\SMTP;
 use PHPMailer\PHPMailer\Exception;

  // get jwt
 $jwt=$_POST['jwt'];

 if($jwt){
    try {

        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        // check for post vars
        if(!empty($_POST['method'])) {
            $method = $_POST['method'];

            if($method == 'sendAdminMail') {
                if(isset($_POST['subject']) && isset($_POST['body'])) {

                    //Create an instance; passing `true` enables exceptions
                    $mail = new PHPMailer(true);

                    //Server settings
                    $mail->isSMTP();                                        //Send using SMTP
                    $mail->Host       = $MAIL_Server;                       //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                               //Enable SMTP authentication
                    $mail->Username   = $MAIL_User;                         //SMTP username
                    $mail->Password   = $MAIL_Pass;                         //SMTP password
                    $mail->SMTPSecure = $MAIL_Encryption;                   //Enable implicit TLS encryption
                    $mail->Port       = $MAIL_Port;                         //TCP port to connect to; use 587 if you have  set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                    
                    //Recipients
                    $mail->setFrom($APP_admin_from_address);
                    $mail->addAddress($APP_admin_to_address);                  //Add a recipient. Name is optional     
                    $mail->addReplyTo($APP_admin_from_address);             //Add a reply address
                    //$mail->addCC('cc@example.com');                       //Add a cc address
                    //$mail->addBCC('bcc@example.com');                     //Add a bcc address

                    //Attachments
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                    
                    //Content
                    $mail->isHTML(true);                                    //Set email format to HTML
                    $mail->Subject = $_POST['subject'];                     //the subject
                    $mail->Body    = $_POST['body'];                        //the mail html body
                    //$mail->AltBody = $_POST['body'];                      // This is the body in plain text for non-HTML mail clients

                    //send the mail
                    $mail->send();
                    echo 'SUCCESS: Message has been sent';
                } else {
                    // set response code
                    http_response_code(400);        
                    // show error message
                    echo "ERROR: mising parameters.";
                }
            }
            if($method == 'sendTOTPqr') {
                
            }

        } else {
            // set response code
            http_response_code(400);        
            // show error message
            echo "ERROR: no method provided!";
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