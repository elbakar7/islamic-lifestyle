<?php

namespace App\Controllers;

use App\Models\User;

/**
 * Dashboard Controller
 * 
 * Handles user dashboard
 */
class DashboardController extends BaseController
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    /**
     * Show dashboard
     */
    public function index()
    {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        
        // Get user stats
        $stats = $this->userModel->getUserStats($userId);
        
        // Get recent activities
        $stmt = db()->prepare("
            SELECT * FROM activity_logs 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        $recentActivities = $stmt->fetchAll();
        
        // Get user's recent fatwas
        $stmt = db()->prepare("
            SELECT * FROM fatwas 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        $stmt->execute([$userId]);
        $recentFatwas = $stmt->fetchAll();
        
        $this->view('dashboard', compact('stats', 'recentActivities', 'recentFatwas'));
    }
}