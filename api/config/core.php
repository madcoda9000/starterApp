<?php
    // show error reporting
    error_reporting(E_ALL);

    // set your default time-zone
    date_default_timezone_set('Europe/Berlin');

    // Database variables
    $DB_host = "YOUR_DB_IP";
    $DB_user = "YOUR_DB_USER";
    $DB_pass = "YOUR_DB_PASS";
    $DB_name = "YOUR_DB_NAME";

    // mail variables
    $MAIL_Server = "YOUR_SMTP_SERVER";
    $MAIL_Port = "587";
    $MAIL_User = "YOUR_SMTP_USERNAME";
    $MAIL_Pass = "YOUR_SMTP_PASWD";
    $MAIL_Encryption = "PHPMailer::ENCRYPTION_STARTTLS"; //possible values are: PHPMailer::ENCRYPTION_STARTTLS (STARTTLS) or PHPMailer::ENCRYPTION_SMTPS (SSL)
    $MAIL_useSmtpAuth = true;

    // variables used for jwt
    $key = "lkjh23409ufvcwne05tu902344u9r78dft8347g94hzt03485g6h374057bß3476bvß34907634b0ß987";
    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); // valid for 1 hour
    $issuer = "http://localhost/shpass/";

    // application variables
    $APP_title = "starterAPP";   // your application title
    $APP_title_description = "customize your starter app.";  // your application description
    $APP_allow_signup = true; // should registering into your app be allowed
    $APP_admin_from_address = "YOUR@FROM.ADDRESS";
    $APP_admin_to_address = 'YOUR@TO.ADDRESS';
    $APP_failedLogonCount = 3; // amount of failed logon attempts before the account will be disabled
    $APP_lockOutMail = true;  // send an admin mail whe a user is locked out due too many login attempts
?>
