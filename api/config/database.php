<?php
// used to get mysql database connection
class Database{
 
    // specify your own database credentials
    private $host = "192.168.2.234";
    private $db_name = "shpass";
    private $username = "shpass";
    private $password = "Diu1.SHPASS";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>