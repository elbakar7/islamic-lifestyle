<?php

namespace App\Controllers;

/**
 * Base Controller
 * 
 * Contains common functionality for all controllers
 */
abstract class BaseController
{
    /**
     * Render a view
     */
    protected function view($view, $data = [])
    {
        // Extract data to variables
        extract($data);
        
        // Build view path
        $viewPath = APP_PATH . '/views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: {$view}");
        }
        
        // Start output buffering
        ob_start();
        
        // Include view
        require $viewPath;
        
        // Get content
        $content = ob_get_clean();
        
        // Include layout if not ajax request
        if (!$this->isAjax()) {
            require APP_PATH . '/views/layouts/main.php';
        } else {
            echo $content;
        }
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url, $statusCode = 302)
    {
        redirect($url, $statusCode);
    }
    
    /**
     * Check if request is AJAX
     */
    protected function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Check if request is POST
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Get request input
     */
    protected function input($key = null, $default = null)
    {
        if ($key === null) {
            return array_merge($_GET, $_POST);
        }
        
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrf()
    {
        if (!$this->isPost()) {
            return true;
        }
        
        $token = $this->input('csrf_token');
        
        if (!verifyCsrfToken($token)) {
            $this->json(['error' => 'Invalid CSRF token'], 403);
        }
        
        return true;
    }
    
    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!isLoggedIn()) {
            flash('error', 'Please login to continue');
            $this->redirect('/login?redirect=' . urlencode(currentUrl()));
        }
    }
    
    /**
     * Require admin role
     */
    protected function requireAdmin()
    {
        $this->requireAuth();
        
        if (!isAdmin()) {
            flash('error', 'Access denied. Admin privileges required.');
            $this->redirect('/');
        }
    }
    
    /**
     * Require scholar role
     */
    protected function requireScholar()
    {
        $this->requireAuth();
        
        if (!isScholar() && !isAdmin()) {
            flash('error', 'Access denied. Scholar privileges required.');
            $this->redirect('/');
        }
    }
    
    /**
     * Validate input data
     */
    protected function validate($data, $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $fieldRules = explode('|', $fieldRules);
            
            foreach ($fieldRules as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleValue = $ruleParts[1] ?? null;
                
                switch ($ruleName) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field][] = ucfirst($field) . ' is required';
                        }
                        break;
                        
                    case 'email':
                        if ($value && !isValidEmail($value)) {
                            $errors[$field][] = 'Invalid email format';
                        }
                        break;
                        
                    case 'phone':
                        if ($value && !isValidPhone($value)) {
                            $errors[$field][] = 'Invalid phone number format';
                        }
                        break;
                        
                    case 'min':
                        if ($value && strlen($value) < $ruleValue) {
                            $errors[$field][] = ucfirst($field) . " must be at least {$ruleValue} characters";
                        }
                        break;
                        
                    case 'max':
                        if ($value && strlen($value) > $ruleValue) {
                            $errors[$field][] = ucfirst($field) . " must not exceed {$ruleValue} characters";
                        }
                        break;
                        
                    case 'numeric':
                        if ($value && !is_numeric($value)) {
                            $errors[$field][] = ucfirst($field) . ' must be numeric';
                        }
                        break;
                        
                    case 'confirmed':
                        $confirmField = $field . '_confirmation';
                        if ($value !== ($data[$confirmField] ?? null)) {
                            $errors[$field][] = ucfirst($field) . ' confirmation does not match';
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Paginate query results
     */
    protected function paginate($query, $perPage = null)
    {
        $perPage = $perPage ?: config('pagination.per_page', 20);
        $page = max(1, (int)$this->input('page', 1));
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countQuery = preg_replace('/SELECT.*?FROM/is', 'SELECT COUNT(*) as total FROM', $query);
        $countQuery = preg_replace('/ORDER BY.*$/is', '', $countQuery);
        
        $stmt = db()->prepare($countQuery);
        $stmt->execute();
        $total = $stmt->fetch()['total'];
        
        // Get paginated results
        $query .= " LIMIT {$perPage} OFFSET {$offset}";
        $stmt = db()->prepare($query);
        $stmt->execute();
        $items = $stmt->fetchAll();
        
        return [
            'items' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }
}