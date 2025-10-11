<?php
if (!defined('DB_SERVER')) {
    require_once("../initialize.php");
}

class DBConnection {
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;

    public $conn;

    public function __construct() {
        // Asignar valores desde las constantes globales
        $this->host = DB_SERVER;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->database = DB_NAME;
        $this->port = defined('DB_PORT') ? DB_PORT : 3306;

        // Crear conexión
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

        // Configura la conexión para UTF-8
        $this->conn->set_charset("utf8mb4");
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
