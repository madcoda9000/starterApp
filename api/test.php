<?php
 //required headers
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Methods: POST");
 header("Access-Control-Max-Age: 3600");
 header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 // required to encode json web token
 include_once 'config/core.php';
 include_once 'vendor/autoload.php';

 ORM::configure('mysql:host=' . $DB_host . ';dbname='.$DB_name);
 ORM::configure('username', $DB_user);
 ORM::configure('password', $DB_pass);

 $user = ORM::for_table('users')->where('firstname', 'Sascha')->find_one();
 echo $user->firstname . " " . $user->lastname;
 $user->lastname = "Heimann";
 $user->save();
 $user = ORM::for_table('users')->where('firstname', 'Sascha')->find_one();
 echo $user->firstname . " " . $user->lastname;
 ?>