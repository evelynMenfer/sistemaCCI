<?php
/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN GLOBAL DEL SISTEMA
|--------------------------------------------------------------------------
| Este archivo permite conectar automáticamente tu sistema PHP
| con la base de datos de Railway (en Render) o tu entorno local.
| Funciona sin cambios entre ambos entornos.
|--------------------------------------------------------------------------
*/

// 🔹 Datos del usuario administrador por defecto
$dev_data = array(
    'id' => '1',
    'firstname' => 'administrador',
    'lastname' => '',
    'username' => 'admin',
    'password' => '21232f297a57a5a743894a0e4a801fc3', // admin
    'last_login' => '',
    'date_updated' => '',
    'date_added' => ''
);

// ======================================================
// 🔹 CONFIGURACIÓN AUTOMÁTICA SEGÚN ENTORNO
// ======================================================

// Detectar si Render/Railway ha enviado la variable de conexión completa
$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // Ejemplo: mysql://root:password@host:port/database
    $url = parse_url($databaseUrl);

    define('DB_SERVER', $url['host']);
    define('DB_USERNAME', $url['user']);
    define('DB_PASSWORD', $url['pass']);
    define('DB_NAME', ltrim($url['path'], '/'));
    define('DB_PORT', isset($url['port']) ? $url['port'] : 3306);
} else {
    // 🔹 Configuración LOCAL (phpMyAdmin)
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'dbinventariosprueba');
    define('DB_PORT', 3306);
}

// ======================================================
// 🔹 URL BASE AUTOMÁTICA
// ======================================================
$render_url = getenv('RENDER_EXTERNAL_URL') ?: 'http://localhost/sisinventarios/';
if (!defined('base_url')) define('base_url', $render_url);

// 🔹 Ruta raíz del proyecto
if (!defined('base_app')) define('base_app', str_replace('\\', '/', __DIR__) . '/');

// 🔹 Datos del usuario admin
if (!defined('dev_data')) define('dev_data', $dev_data);
?>
