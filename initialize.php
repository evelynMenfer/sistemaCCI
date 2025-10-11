<?php
// Cargar configuración global
require_once(__DIR__ . "/config.php");

// Activar zona horaria y errores (útil para debug)
date_default_timezone_set('America/Mexico_City');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión si aún no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cargar conexión a base de datos
require_once(__DIR__ . "/classes/DBConnection.php");

// Crear instancia global para acceso fácil
$conn = new DBConnection();
?>
