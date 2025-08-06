<?php
/**
 * Bootstrap File
 * 
 * This file initializes the application, loads dependencies, and sets up the environment
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('STORAGE_PATH', BASE_PATH . '/storage');

// Load Composer autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

// Set timezone
date_default_timezone_set(env('APP_TIMEZONE', 'Africa/Dar_es_Salaam'));

// Error reporting
if (env('APP_DEBUG', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => env('SESSION_LIFETIME', 120) * 60,
        'cookie_secure' => env('SESSION_SECURE_COOKIE', true),
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}

// Load configuration
$config = [];
foreach (glob(CONFIG_PATH . '/*.php') as $configFile) {
    $configName = basename($configFile, '.php');
    $config[$configName] = require $configFile;
}

// Make config globally accessible
$GLOBALS['config'] = $config;

// Initialize database connection
try {
    $dbConfig = $config['database']['connections'][$config['database']['default']];
    $dsn = sprintf(
        '%s:host=%s;port=%s;dbname=%s;charset=%s',
        $dbConfig['driver'],
        $dbConfig['host'],
        $dbConfig['port'],
        $dbConfig['database'],
        $dbConfig['charset']
    );
    
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
    
    // Make PDO instance globally accessible
    $GLOBALS['db'] = $pdo;
    
} catch (PDOException $e) {
    if (env('APP_DEBUG', false)) {
        die('Database connection failed: ' . $e->getMessage());
    } else {
        die('Database connection failed. Please try again later.');
    }
}

// Initialize logger
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('app');
$log->pushHandler(new StreamHandler(BASE_PATH . '/storage/logs/app.log', Logger::WARNING));
$GLOBALS['log'] = $log;

// Load helper functions
require_once BASE_PATH . '/includes/helpers.php';