<?php
if (!defined('DB_SERVER')) {
    require_once("../config.php");
}

class DBConnection {
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;

    public $conn;

    public function __construct() {
        $this->host = DB_SERVER;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->database = DB_NAME;
        $this->port = DB_PORT;

        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            $this->port
        );

        if ($this->conn->connect_error) {
            die('❌ Error al conectar con la base de datos: ' . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
