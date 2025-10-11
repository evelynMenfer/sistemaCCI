<?php
/*
|--------------------------------------------------------------------------
| INITIALIZE.PHP
|--------------------------------------------------------------------------
| Este archivo inicializa todo el entorno del sistema.
| Se encarga de:
|  - Cargar la configuración global (config.php)
|  - Iniciar sesión de usuario
|  - Conectar automáticamente a la base de datos
|  - Definir zona horaria y manejo de errores
|--------------------------------------------------------------------------
*/

// 🔹 Cargar configuración principal
require_once(__DIR__ . '/config.php');

// 🔹 Zona horaria por defecto
date_default_timezone_set('America/Mexico_City');

// 🔹 Mostrar errores solo en entorno local
if (getenv('RENDER') || getenv('RAILWAY_ENVIRONMENT')) {
    // Producción: oculta errores
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    // Local: muestra errores para depuración
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// 🔹 Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔹 Incluir clase de conexión
require_once(__DIR__ . '/classes/DBConnection.php');

// 🔹 Crear instancia global de conexión
try {
    $conn = new DBConnection();
} catch (Exception $e) {
    die('❌ Error al conectar con la base de datos: ' . $e->getMessage());
}

// 🔹 Confirmar conexión (solo para debug local)
if (!getenv('RENDER')) {
    // echo "✅ Conexión exitosa a la base de datos.";
}
?>
