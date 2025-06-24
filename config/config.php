<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

define('APP_NAME', 'Perfume Scent Recommender');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/scent-recommender/');

define('FRAGRANTICA_BASE_URL', 'https://www.fragrantica.com');

define('ROOT_PATH', dirname(__DIR__));
define('VIEWS_PATH', ROOT_PATH . '/views/');
define('MODELS_PATH', ROOT_PATH . '/models/');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers/');

spl_autoload_register(function ($className) {
    $paths = [
        MODELS_PATH . strtolower($className) . '.php',
        CONTROLLERS_PATH . strtolower($className) . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function redirectTo($url) {
    header("Location: $url");
    exit();
}

function setFlashMessage($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function getFlashMessage($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}
?>