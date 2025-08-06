<?php
/**
 * Helper Functions
 * 
 * Common utility functions used throughout the application
 */

/**
 * Get environment variable with default value
 */
function env($key, $default = null) {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    
    if ($value === false) {
        return $default;
    }
    
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }
    
    return $value;
}

/**
 * Get configuration value using dot notation
 */
function config($key, $default = null) {
    $keys = explode('.', $key);
    $value = $GLOBALS['config'];
    
    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }
    
    return $value;
}

/**
 * Get database connection
 */
function db() {
    return $GLOBALS['db'];
}

/**
 * Get logger instance
 */
function logger() {
    return $GLOBALS['log'];
}

/**
 * Redirect to URL
 */
function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit();
}

/**
 * Get current URL
 */
function currentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Generate URL
 */
function url($path = '') {
    $baseUrl = rtrim(config('app.url', 'http://localhost'), '/');
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Generate asset URL
 */
function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Escape HTML
 */
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user
 */
function currentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    static $user = null;
    
    if ($user === null) {
        $stmt = db()->prepare('SELECT * FROM users WHERE id = ? AND is_active = 1');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
    
    return $user;
}

/**
 * Check if user has role
 */
function hasRole($role) {
    $user = currentUser();
    return $user && $user['role'] === $role;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return hasRole('admin');
}

/**
 * Check if user is scholar
 */
function isScholar() {
    return hasRole('scholar');
}

/**
 * Generate CSRF token
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * CSRF token field
 */
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . e(generateCsrfToken()) . '">';
}

/**
 * Flash message
 */
function flash($key, $value = null) {
    if ($value === null) {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    
    $_SESSION['flash'][$key] = $value;
}

/**
 * Old input value
 */
function old($key, $default = '') {
    $value = $_SESSION['old_input'][$key] ?? $default;
    unset($_SESSION['old_input'][$key]);
    return $value;
}

/**
 * Store old input
 */
function storeOldInput($data) {
    $_SESSION['old_input'] = $data;
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Tanzanian format)
 */
function isValidPhone($phone) {
    // Remove spaces and dashes
    $phone = str_replace([' ', '-'], '', $phone);
    
    // Check if it matches Tanzanian phone format
    return preg_match('/^(\+?255|0)[67]\d{8}$/', $phone);
}

/**
 * Format phone number
 */
function formatPhone($phone) {
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    
    if (strpos($phone, '+255') === 0) {
        return $phone;
    } elseif (strpos($phone, '0') === 0) {
        return '+255' . substr($phone, 1);
    }
    
    return '+255' . $phone;
}

/**
 * Generate random string
 */
function generateRandomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Format date
 */
function formatDate($date, $format = 'd/m/Y') {
    if (!$date) return '';
    
    if (is_string($date)) {
        $date = new DateTime($date);
    }
    
    return $date->format($format);
}

/**
 * Format datetime
 */
function formatDateTime($date, $format = 'd/m/Y H:i') {
    return formatDate($date, $format);
}

/**
 * Get time ago
 */
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return formatDate($datetime);
    }
}

/**
 * Sanitize input
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Create slug from string
 */
function createSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

/**
 * Get Hijri date
 */
function getHijriDate($gregorianDate = null) {
    if (!$gregorianDate) {
        $gregorianDate = date('Y-m-d');
    }
    
    // Simple Hijri date calculation (can be replaced with more accurate API)
    $westernDate = new DateTime($gregorianDate);
    $islamicDate = new DateTime('622-07-16'); // Hijra date
    
    $diff = $westernDate->diff($islamicDate);
    $years = floor($diff->days / 354.367);
    
    return [
        'year' => $years,
        'formatted' => $years . ' AH'
    ];
}

/**
 * Log activity
 */
function logActivity($action, $module = null, $details = null) {
    try {
        $stmt = db()->prepare('
            INSERT INTO activity_logs (user_id, action, module, details, ip_address)
            VALUES (?, ?, ?, ?, ?)
        ');
        
        $stmt->execute([
            $_SESSION['user_id'] ?? null,
            $action,
            $module,
            $details ? json_encode($details) : null,
            $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    } catch (Exception $e) {
        logger()->error('Failed to log activity: ' . $e->getMessage());
    }
}

/**
 * Format currency
 */
function formatCurrency($amount, $currency = 'TZS') {
    $symbols = [
        'TZS' => 'TSh',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£'
    ];
    
    $symbol = $symbols[$currency] ?? $currency;
    return $symbol . ' ' . number_format($amount, 2);
}

/**
 * Get prayer time name in Swahili
 */
function getPrayerNameSwahili($prayer) {
    $names = [
        'fajr' => 'Alfajiri',
        'sunrise' => 'Macheo',
        'dhuhr' => 'Adhuhuri',
        'asr' => 'Alasiri',
        'maghrib' => 'Magharibi',
        'isha' => 'Isha'
    ];
    
    return $names[strtolower($prayer)] ?? $prayer;
}