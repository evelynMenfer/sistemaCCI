<?php
if(!defined('DB_SERVER')){
    require_once("../initialize.php");
}

class DBConnection {

    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    
    public $conn;
    
    public function __construct() {

        if (!isset($this->conn)) {
            
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->conn->connect_error) {
                die('No se puede conectar al servidor de la base de datos: ' . $this->conn->connect_error);
            }

            // Configura la conexión para UTF-8
            $this->conn->set_charset("utf8mb4");
        }    
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
