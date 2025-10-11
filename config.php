<?php
$dev_data = array(
    'id' => '1',
    'firstname' => 'administrador',
    'lastname' => '',
    'username' => 'admin',
    'password' => '21232f297a57a5a743894a0e4a801fc3',
    'last_login' => '',
    'date_updated' => '',
    'date_added' => ''
);

/**
 * CONFIGURACIÓN DE CONEXIÓN PARA RAILWAY
 * Usa variables del entorno si existen, o valores por defecto locales.
 */
if (!defined('DB_SERVER'))   define('DB_SERVER', getenv('MYSQLHOST') ?: 'mysql.railway.internal');
if (!defined('DB_USERNAME')) define('DB_USERNAME', getenv('MYSQLUSER') ?: 'root');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', getenv('MYSQLPASSWORD') ?: 'ONvxHSyJvCLXPwAhMhnrrrYbnLeUpVmb');
if (!defined('DB_NAME'))     define('DB_NAME', getenv('MYSQLDATABASE') ?: 'railway');
if (!defined('DB_PORT'))     define('DB_PORT', getenv('MYSQLPORT') ?: 3306);

// URL BASE AUTOMÁTICA
$render_url = getenv('RENDER_EXTERNAL_URL') ?: 'http://localhost/sisinventarios/';
if (!defined('base_url')) define('base_url', $render_url);

if (!defined('base_app')) define('base_app', str_replace('\\', '/', __DIR__) . '/');
if (!defined('dev_data')) define('dev_data', $dev_data);
?>
