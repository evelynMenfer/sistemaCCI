<?php
if (!defined('DB_SERVER')) {
    // Si no se ha cargado la configuración aún, la incluimos
    require_once(__DIR__ . '/../config.php');
}

class DBConnection {
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;
    public $conn;

    public function __construct() {
        // Asignar las variables definidas globalmente
        $this->host = DB_SERVER;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->database = DB_NAME;
        $this->port = defined('DB_PORT') ? DB_PORT : 3306;

        // Intentar conexión
        $this->connect();
    }

    private function connect() {
        // Crear conexión MySQL segura
        $this->conn = @new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            $this->port
        );

        // Manejo de errores de conexión
        if ($this->conn->connect_error) {
            // Mostrar mensaje claro (útil para debug en Render)
            die('❌ Error al conectar con la base de datos (' . 
                $this->conn->connect_errno . '): ' . 
                $this->conn->connect_error . 
                '<br>Servidor: ' . $this->host . 
                '<br>Base de datos: ' . $this->database);
        }

        // Forzar UTF-8 para acentos y caracteres especiales
        $this->conn->set_charset("utf8mb4");
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
