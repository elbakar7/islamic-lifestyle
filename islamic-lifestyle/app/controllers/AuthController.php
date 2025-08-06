<?php

namespace App\Controllers;

use App\Models\User;

/**
 * Authentication Controller
 * 
 * Handles user authentication, registration, and password management
 */
class AuthController extends BaseController
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.login');
    }
    
    /**
     * Process login
     */
    public function login()
    {
        $this->validateCsrf();
        
        $email = sanitize($this->input('email'));
        $password = $this->input('password');
        $remember = $this->input('remember');
        
        // Validate input
        $errors = $this->validate([
            'email' => $email,
            'password' => $password
        ], [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            storeOldInput(['email' => $email]);
            flash('errors', $errors);
            $this->redirect('/login');
        }
        
        // Authenticate user
        $user = $this->userModel->authenticate($email, $password);
        
        if (!$user) {
            storeOldInput(['email' => $email]);
            flash('error', 'Invalid email or password');
            logActivity('login_failed', 'auth', ['email' => $email]);
            $this->redirect('/login');
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        
        // Remember me
        if ($remember) {
            $token = generateRandomString(64);
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
            
            // Store token in database (you may want to create a separate table for this)
            // For now, we'll skip this implementation
        }
        
        // Log activity
        logActivity('login', 'auth');
        
        // Redirect
        $redirect = $this->input('redirect', '/dashboard');
        flash('success', 'Welcome back, ' . $user['full_name'] . '!');
        $this->redirect($redirect);
    }
    
    /**
     * Show registration form
     */
    public function showRegister()
    {
        if (isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $regions = config('app.regions');
        $this->view('auth.register', compact('regions'));
    }
    
    /**
     * Process registration
     */
    public function register()
    {
        $this->validateCsrf();
        
        $data = [
            'username' => sanitize($this->input('username')),
            'email' => sanitize($this->input('email')),
            'password' => $this->input('password'),
            'password_confirmation' => $this->input('password_confirmation'),
            'full_name' => sanitize($this->input('full_name')),
            'phone' => sanitize($this->input('phone')),
            'region' => sanitize($this->input('region'))
        ];
        
        // Validate input
        $errors = $this->validate($data, [
            'username' => 'required|min:3|max:20',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'full_name' => 'required|max:100',
            'phone' => 'phone',
            'region' => 'required'
        ]);
        
        // Check if username exists
        if ($this->userModel->usernameExists($data['username'])) {
            $errors['username'][] = 'Username already taken';
        }
        
        // Check if email exists
        if ($this->userModel->emailExists($data['email'])) {
            $errors['email'][] = 'Email already registered';
        }
        
        if (!empty($errors)) {
            storeOldInput($data);
            flash('errors', $errors);
            $this->redirect('/register');
        }
        
        // Create user
        try {
            $user = $this->userModel->createUser($data);
            
            // Send verification email (implement email sending)
            // $this->sendVerificationEmail($user);
            
            // Log activity
            logActivity('register', 'auth', ['user_id' => $user['id']]);
            
            // Auto login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['full_name'];
            
            flash('success', 'Registration successful! Welcome to Islamic Lifestyle.');
            $this->redirect('/dashboard');
            
        } catch (\Exception $e) {
            logger()->error('Registration failed: ' . $e->getMessage());
            flash('error', 'Registration failed. Please try again.');
            storeOldInput($data);
            $this->redirect('/register');
        }
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        logActivity('logout', 'auth');
        
        // Clear session
        session_destroy();
        
        // Clear remember cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        flash('success', 'You have been logged out successfully.');
        $this->redirect('/');
    }
    
    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        if (isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.forgot-password');
    }
    
    /**
     * Process forgot password
     */
    public function forgotPassword()
    {
        $this->validateCsrf();
        
        $email = sanitize($this->input('email'));
        
        // Validate email
        $errors = $this->validate(['email' => $email], ['email' => 'required|email']);
        
        if (!empty($errors)) {
            flash('errors', $errors);
            storeOldInput(['email' => $email]);
            $this->redirect('/forgot-password');
        }
        
        // Generate reset token
        $token = $this->userModel->generateResetToken($email);
        
        if ($token) {
            // Send reset email (implement email sending)
            // $this->sendResetEmail($email, $token);
            
            logActivity('password_reset_requested', 'auth', ['email' => $email]);
        }
        
        // Always show success message (security)
        flash('success', 'If the email exists, a password reset link has been sent.');
        $this->redirect('/login');
    }
    
    /**
     * Show reset password form
     */
    public function showResetPassword($token)
    {
        if (isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $user = $this->userModel->verifyResetToken($token);
        
        if (!$user) {
            flash('error', 'Invalid or expired reset token.');
            $this->redirect('/login');
        }
        
        $this->view('auth.reset-password', compact('token'));
    }
    
    /**
     * Process reset password
     */
    public function resetPassword()
    {
        $this->validateCsrf();
        
        $token = sanitize($this->input('token'));
        $password = $this->input('password');
        $passwordConfirmation = $this->input('password_confirmation');
        
        // Validate input
        $errors = $this->validate([
            'password' => $password,
            'password_confirmation' => $passwordConfirmation
        ], [
            'password' => 'required|min:6|confirmed'
        ]);
        
        if (!empty($errors)) {
            flash('errors', $errors);
            $this->redirect('/reset-password/' . $token);
        }
        
        // Verify token again
        $user = $this->userModel->verifyResetToken($token);
        
        if (!$user) {
            flash('error', 'Invalid or expired reset token.');
            $this->redirect('/login');
        }
        
        // Update password
        $this->userModel->updatePassword($user['id'], $password);
        
        logActivity('password_reset', 'auth', ['user_id' => $user['id']]);
        
        flash('success', 'Password reset successful. Please login with your new password.');
        $this->redirect('/login');
    }
}