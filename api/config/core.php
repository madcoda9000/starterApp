<?php
    // show error reporting
    error_reporting(E_ALL);

    // set your default time-zone
    date_default_timezone_set('Europe/Berlin');

    // Database variables
    $DB_host = "192.168.2.234";
    $DB_user = "shpass";
    $DB_pass = "Diu1.SHPASS";
    $DB_name = "shpass";

    // mail variables
    $MAIL_Server = "smtp.gmail.com";
    $MAIL_Port = "587";
    $MAIL_User = "sascha.heimann@gmail.com";
    $MAIL_Pass = "lfllzcjmtmungdxg";
    $MAIL_Encryption = "PHPMailer::ENCRYPTION_STARTTLS"; //possible values are: PHPMailer::ENCRYPTION_STARTTLS (STARTTLS) or PHPMailer::ENCRYPTION_SMTPS (SSL)

    // variables used for jwt
    $key = "lkjh23409ufvcwne05tu902344u9r78dft8347g94hzt03485g6h374057bß3476bvß34907634b0ß987";
    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); // valid for 1 hour
    $issuer = "http://localhost/shpass/";

    // application variables
    $APP_title = "starterAPP";   // your application title
    $APP_title_description = "customize your starter app.";  // your application description
    $APP_allow_signup = true; // should registering into your app be allowed
    $APP_admin_from_address = "sascha.heimann@gmail.com";
    $APP_admin_to_address = 'sascha.heimann@gmail.com';
    $APP_failedLogonCount = 3; // amount of failed logon attempts before the account will be disabled
    $APP_lockOutMail = true;  // send an admin mail whe a user is locked out due too many login attempts
?>