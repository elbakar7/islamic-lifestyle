<?php
/**
 * Islamic Lifestyle Platform
 * 
 * Main entry point for the application
 */

// Load bootstrap
require_once dirname(__DIR__) . '/includes/bootstrap.php';

// Simple router
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove base path if any
$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath !== '/') {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Clean the URI
$requestUri = '/' . trim($requestUri, '/');

// Define routes
$routes = [
    // Public routes
    'GET /' => 'HomeController@index',
    'GET /prayer-times' => 'PrayerController@index',
    'POST /prayer-times/location' => 'PrayerController@setLocation',
    'GET /quran' => 'QuranController@index',
    'GET /quran/surah/{id}' => 'QuranController@surah',
    'GET /hadith' => 'HadithController@index',
    'GET /fatwa' => 'FatwaController@index',
    'GET /fatwa/{id}' => 'FatwaController@show',
    'GET /zakat-calculator' => 'ZakatController@index',
    'POST /zakat-calculator' => 'ZakatController@calculate',
    'GET /inheritance-calculator' => 'InheritanceController@index',
    'POST /inheritance-calculator' => 'InheritanceController@calculate',
    'GET /businesses' => 'BusinessController@index',
    'GET /businesses/{id}' => 'BusinessController@show',
    'GET /donate' => 'DonationController@index',
    'POST /donate' => 'DonationController@process',
    'GET /articles' => 'ArticleController@index',
    'GET /articles/{slug}' => 'ArticleController@show',
    'GET /calendar' => 'CalendarController@index',
    
    // Authentication routes
    'GET /login' => 'AuthController@showLogin',
    'POST /login' => 'AuthController@login',
    'GET /register' => 'AuthController@showRegister',
    'POST /register' => 'AuthController@register',
    'GET /logout' => 'AuthController@logout',
    'GET /forgot-password' => 'AuthController@showForgotPassword',
    'POST /forgot-password' => 'AuthController@forgotPassword',
    'GET /reset-password/{token}' => 'AuthController@showResetPassword',
    'POST /reset-password' => 'AuthController@resetPassword',
    
    // User dashboard routes
    'GET /dashboard' => 'DashboardController@index',
    'GET /profile' => 'ProfileController@index',
    'POST /profile' => 'ProfileController@update',
    'GET /my-fatwas' => 'FatwaController@myFatwas',
    'POST /fatwa/ask' => 'FatwaController@ask',
    
    // Admin routes
    'GET /admin' => 'Admin\DashboardController@index',
    'GET /admin/users' => 'Admin\UserController@index',
    'GET /admin/fatwas' => 'Admin\FatwaController@index',
    'POST /admin/fatwas/{id}/answer' => 'Admin\FatwaController@answer',
    'GET /admin/businesses' => 'Admin\BusinessController@index',
    'POST /admin/businesses/{id}/verify' => 'Admin\BusinessController@verify',
    'GET /admin/donations' => 'Admin\DonationController@index',
    'GET /admin/articles' => 'Admin\ArticleController@index',
    'GET /admin/settings' => 'Admin\SettingsController@index',
    'POST /admin/settings' => 'Admin\SettingsController@update',
];

// Match route
$routeFound = false;
$params = [];

foreach ($routes as $route => $handler) {
    list($method, $pattern) = explode(' ', $route, 2);
    
    if ($method !== $requestMethod) {
        continue;
    }
    
    // Convert route pattern to regex
    $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
    $regex = '#^' . $regex . '$#';
    
    if (preg_match($regex, $requestUri, $matches)) {
        $routeFound = true;
        
        // Extract parameters
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }
        
        // Parse handler
        list($controllerName, $methodName) = explode('@', $handler);
        
        // Build controller path
        $controllerPath = APP_PATH . '/controllers/' . str_replace('\\', '/', $controllerName) . '.php';
        
        if (!file_exists($controllerPath)) {
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Controller not found: ' . $controllerName;
            exit;
        }
        
        // Load controller
        require_once $controllerPath;
        
        // Get full controller class name
        $controllerClass = 'App\\Controllers\\' . $controllerName;
        
        if (!class_exists($controllerClass)) {
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Controller class not found: ' . $controllerClass;
            exit;
        }
        
        // Create controller instance
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $methodName)) {
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Method not found: ' . $methodName;
            exit;
        }
        
        // Call controller method with parameters
        call_user_func_array([$controller, $methodName], $params);
        break;
    }
}

// Handle 404
if (!$routeFound) {
    header('HTTP/1.0 404 Not Found');
    require_once APP_PATH . '/views/errors/404.php';
}