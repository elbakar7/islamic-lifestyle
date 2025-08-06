<?php

namespace App\Controllers;

use App\Models\User;

/**
 * Home Controller
 * 
 * Handles the main landing page
 */
class HomeController extends BaseController
{
    /**
     * Show home page
     */
    public function index()
    {
        // Get current prayer times for default city (Dar es Salaam)
        $defaultCity = 'dar-es-salaam';
        $today = date('Y-m-d');
        
        // Try to get prayer times from cache
        $stmt = db()->prepare("
            SELECT * FROM prayer_times 
            WHERE city = ? AND date = ?
            LIMIT 1
        ");
        $stmt->execute([$defaultCity, $today]);
        $prayerTimes = $stmt->fetch();
        
        // Get statistics
        $stats = $this->getStatistics();
        
        // Get recent articles
        $stmt = db()->prepare("
            SELECT a.*, u.full_name as author_name
            FROM articles a
            JOIN users u ON a.author_id = u.id
            WHERE a.status = 'published'
            ORDER BY a.published_at DESC
            LIMIT 3
        ");
        $stmt->execute();
        $recentArticles = $stmt->fetchAll();
        
        // Get upcoming Islamic events
        $stmt = db()->prepare("
            SELECT * FROM islamic_events
            WHERE gregorian_date >= CURDATE()
            ORDER BY gregorian_date ASC
            LIMIT 5
        ");
        $stmt->execute();
        $upcomingEvents = $stmt->fetchAll();
        
        $this->view('home', compact('prayerTimes', 'stats', 'recentArticles', 'upcomingEvents'));
    }
    
    /**
     * Get platform statistics
     */
    private function getStatistics()
    {
        $stats = [];
        
        // Total users
        $stmt = db()->prepare("SELECT COUNT(*) as total FROM users WHERE is_active = 1");
        $stmt->execute();
        $stats['users'] = $stmt->fetch()['total'];
        
        // Total fatwas
        $stmt = db()->prepare("SELECT COUNT(*) as total FROM fatwas WHERE status = 'published'");
        $stmt->execute();
        $stats['fatwas'] = $stmt->fetch()['total'];
        
        // Total donations
        $stmt = db()->prepare("
            SELECT COUNT(*) as count, SUM(amount) as total 
            FROM donations 
            WHERE payment_status = 'completed'
        ");
        $stmt->execute();
        $donation = $stmt->fetch();
        $stats['donations_count'] = $donation['count'];
        $stats['donations_total'] = $donation['total'] ?? 0;
        
        // Total businesses
        $stmt = db()->prepare("SELECT COUNT(*) as total FROM businesses WHERE is_active = 1");
        $stmt->execute();
        $stats['businesses'] = $stmt->fetch()['total'];
        
        return $stats;
    }
}