<?php
 // required to encode json web token
 include_once 'config/core.php';
 include_once 'vendor/autoload.php';

 // create orm instance
 ORM::configure('mysql:host=' . $DB_host . ';dbname='.$DB_name);
 ORM::configure('username', $DB_user);
 ORM::configure('password', $DB_pass);

// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $totp_secret;
    public $totp_enabled;
    public $accState;
    public $failedLogonCount;
 
    // constructor
    public function __construct(){
    }    
    
    // create new user record
    function create(){

        $new_user = ORM::for_table('users')->create();
    
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        $new_user->firstname = $this->firstname;
        $new_user->lastname = $this->lastname;
        $new_user->email = $this->email;
        $new_user->totp_secret = $this->totp_secret;
        $new_user->totp_enabled = $this->totp_enabled;
        $new_user->password = $password_hash;
        $new_user->appGroup = "users";
        $new_user->accState = 1;
        try {
        $new_user->save();
        if($new_user->id()){
            return true;
        }
        } catch (exception $e) {
            $t = $e->getMessage();
            return false;
        }         
    }

    // disable user
    function disable_user() {
        $edit_user = ORM::for_table('users')->find_one($this->id);
        if($edit_user) {
            $edit_user->accState = 0;
            $edit_user->save();
            return true;
        } else {
            return false;
        }
    }

    // enable user
    function enable_user() {
        $edit_user = ORM::for_table('users')->find_one($this->id);
        if($edit_user) {
            $edit_user->accState = 1;
            $edit_user->save();
            return true;
        } else {
            return false;
        }
    }

        
    // enable totp for user
    function enable_totp() {
    
        // sanitize
        $this->totp_enabled=htmlspecialchars(strip_tags($this->totp_enabled));
        $this->totp_secret=htmlspecialchars(strip_tags($this->totp_secret));

        $edit_user = ORM::for_table('users')->find_one($this->id);
        if($edit_user) {
            $edit_user->totp_enabled = $this->totp_enabled;
            $edit_user->totp_secret = $this->totp_secret;
            $edit_user->save();
            return true;
        } else {
            return false;
        }
    }

    // update totp data
    function update_totp() {
    
        // sanitize
        $this->totp_enabled=htmlspecialchars(strip_tags($this->totp_enabled));
        $this->totp_secret=htmlspecialchars(strip_tags($this->totp_secret));

        $edit_user = ORM::for_table('users')->find_one($this->id);
        if($edit_user) {
            $edit_user->totp_enabled = $this->totp_enabled;
            $edit_user->totp_secret = $this->totp_secret;
            $edit_user->save();
            return true;
        }
        else {
            return false;
        }
    }

    // check if given email exist in the database
    function emailExists(){

        $find_user_by_mail = ORM::for_table('users')->where('email', $this->email)->find_one();
        
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($find_user_by_mail){
    
            // assign values to object properties
            $this->id = $find_user_by_mail->id;
            $this->firstname = $find_user_by_mail->firstname;
            $this->lastname = $find_user_by_mail->lastname;
            $this->password = $find_user_by_mail->password;
            $this->totp_secret = $find_user_by_mail->totp_secret;
            $this->totp_enabled = $find_user_by_mail->totp_enabled;
            $this->accState = $find_user_by_mail->accState;
            $this->failedLogonCount = $find_user_by_mail->failedLogonCount;
    
            // return true because email exists in the database
            return true;
        }
    
        // return false if email does not exist in the database
        return false;
    }

    // reset logon count
    function resetFailedLogonCount() {
        $edit_user = ORM::for_table('users')->find_one($this->id);
        if($edit_user) {
            $edit_user->failedLogonCount=0;
            $edit_user->save();
            return true;
        }
        else {
            return false;
        }
    }
 
    // update a user record
    public function update(){

        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));

        // find the record to update
        $upd_user = ORM::for_table('users')->find_one($this->id);

        // check if we have to change the password too
        $changepw = false;
        if(!empty($this->password)) {
            $this->password=htmlspecialchars(strip_tags($this->password));
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $changepw = true;
        }

        // try to update the user
        if($upd_user){
            $upd_user->firstname = $this->firstname;
            $upd_user->lastname = $this->lastname;
            $upd_user->email = $this->email;
            if($changepw == true) {
                $upd_user->password = $this->password;
            }
            $upd_user->save();
            return true;
        } else {
            return false;
        }
    }
}