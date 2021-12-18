<?php
    // show error reporting
    error_reporting(E_ALL);
    
    // set your default time-zone
    date_default_timezone_set('Europe/Berlin');

    // Database variables
    $DB_host = "";
    $DB_user = "";
    $DB_pass = "";
    $DB_name = "";

    // mail variables
    $MAIL_Server = "";
    $MAIL_Port = "587";
    $MAIL_User = "";
    $MAIL_Pass = "";
    $MAIL_Encryption = "PHPMailer::ENCRYPTION_STARTTLS"; //possible values are: PHPMailer::ENCRYPTION_STARTTLS (STARTTLS) or PHPMailer::ENCRYPTION_SMTPS (SSL)

    // variables used for jwt
    $key = "lkjh23409ufvcwne05tu902344u9r78dft8347g94hzt03485g6h374057bß3476bvß34907634b0ß987";
    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); // valid for 1 hour
    $issuer = "";  // insert here your full web path to your application. For ex.: http://localhost/myApp

    // application variables
    $APP_title = "";   // your application title
    $APP_title_description = "";  // your application description
    $APP_allow_signup = true; // should registering into your app be allowed
    $APP_admin_from_address = ""; // the email address which is used for sendeing mails from the app
    $APP_admin_to_address = ''; // the app admin address
?>