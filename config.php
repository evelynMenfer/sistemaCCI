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

// Detectar si estamos en producción (Render/Railway) o local
$is_production = getenv('RAILWAY_ENVIRONMENT') || getenv('RENDER');

if ($is_production) {
    // 🚀 Producción (Render + Railway)
    if (!defined('DB_SERVER')) define('DB_SERVER', "mysql.railway.internal");
    if (!defined('DB_USERNAME')) define('DB_USERNAME', "root");
    if (!defined('DB_PASSWORD')) define('DB_PASSWORD', "ONvxHSyJvCLXPwAhMhnrrrYbnLeUpVmb");
    if (!defined('DB_NAME')) define('DB_NAME', "railway");
    if (!defined('DB_PORT')) define('DB_PORT', "3306");
    if (!defined('base_url')) define('base_url', 'https://sistemacci.onrender.com/');
} else {
    // 💻 Entorno local
    if (!defined('DB_SERVER')) define('DB_SERVER', "localhost");
    if (!defined('DB_USERNAME')) define('DB_USERNAME', "root");
    if (!defined('DB_PASSWORD')) define('DB_PASSWORD', "");
    if (!defined('DB_NAME')) define('DB_NAME', "dbinventariosprueba");
    if (!defined('DB_PORT')) define('DB_PORT', "3306");
    if (!defined('base_url')) define('base_url', 'http://localhost/sisinventarios/');
}

// Rutas base
if (!defined('base_app')) define('base_app', str_replace('\\', '/', __DIR__) . '/');
if (!defined('dev_data')) define('dev_data', $dev_data);
?>
