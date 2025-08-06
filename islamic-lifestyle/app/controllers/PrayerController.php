<?php

namespace App\Controllers;

use App\Models\PrayerTime;

/**
 * Prayer Controller
 * 
 * Handles prayer times functionality
 */
class PrayerController extends BaseController
{
    private $prayerModel;
    
    public function __construct()
    {
        $this->prayerModel = new PrayerTime();
    }
    
    /**
     * Show prayer times page
     */
    public function index()
    {
        // Get selected city from session or default
        $selectedCity = $_SESSION['prayer_city'] ?? 'dar-es-salaam';
        $selectedDate = $this->input('date', date('Y-m-d'));
        
        // Validate date
        if (!strtotime($selectedDate)) {
            $selectedDate = date('Y-m-d');
        }
        
        // Get prayer times
        $prayerTimes = $this->prayerModel->getPrayerTimes($selectedCity, $selectedDate);
        
        // Get next prayer
        $nextPrayer = $this->prayerModel->getNextPrayer($selectedCity);
        
        // Get cities list
        $cities = config('app.cities');
        
        // Get monthly prayer times if viewing current month
        $monthlyTimes = [];
        if (date('Y-m', strtotime($selectedDate)) === date('Y-m')) {
            $monthlyTimes = $this->prayerModel->getMonthlyPrayerTimes(
                $selectedCity,
                date('Y'),
                date('m')
            );
        }
        
        $this->view('modules.prayer-times', compact(
            'prayerTimes',
            'nextPrayer',
            'cities',
            'selectedCity',
            'selectedDate',
            'monthlyTimes'
        ));
    }
    
    /**
     * Set user's preferred location
     */
    public function setLocation()
    {
        $this->validateCsrf();
        
        $city = sanitize($this->input('city'));
        
        // Validate city
        if (!array_key_exists($city, config('app.cities'))) {
            flash('error', 'Invalid city selected');
            $this->redirect('/prayer-times');
        }
        
        // Store in session
        $_SESSION['prayer_city'] = $city;
        
        // If user is logged in, update their profile
        if (isLoggedIn()) {
            db()->prepare("UPDATE users SET region = ? WHERE id = ?")
                ->execute([$city, $_SESSION['user_id']]);
        }
        
        flash('success', 'Location updated successfully');
        $this->redirect('/prayer-times');
    }
}