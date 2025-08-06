<?php

namespace App\Models;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Prayer Time Model
 * 
 * Handles prayer times data and API integration
 */
class PrayerTime extends BaseModel
{
    protected $table = 'prayer_times';
    
    protected $fillable = [
        'city',
        'date',
        'fajr',
        'sunrise',
        'dhuhr',
        'asr',
        'maghrib',
        'isha',
        'calculation_method'
    ];
    
    private $httpClient;
    
    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => env('ALADHAN_API_URL', 'https://api.aladhan.com/v1/'),
            'timeout' => 10,
            'verify' => false // For development only
        ]);
    }
    
    /**
     * Get prayer times for a city and date
     */
    public function getPrayerTimes($city, $date)
    {
        // First check cache
        $cached = $this->getCachedPrayerTimes($city, $date);
        if ($cached) {
            return $cached;
        }
        
        // If not cached, fetch from API
        $cityData = config('app.cities.' . $city);
        if (!$cityData) {
            return null;
        }
        
        try {
            $prayerTimes = $this->fetchFromApi($cityData['lat'], $cityData['lng'], $date);
            
            // Cache the results
            $this->cachePrayerTimes($city, $date, $prayerTimes);
            
            return $prayerTimes;
        } catch (\Exception $e) {
            logger()->error('Failed to fetch prayer times: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get cached prayer times
     */
    public function getCachedPrayerTimes($city, $date)
    {
        return $this->findBy('city', $city);
    }
    
    /**
     * Fetch prayer times from API
     */
    private function fetchFromApi($latitude, $longitude, $date)
    {
        try {
            $response = $this->httpClient->get('timings/' . $date, [
                'query' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'method' => 2, // Islamic Society of North America (ISNA)
                    'school' => 1  // Shafi
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if ($data['code'] !== 200) {
                throw new \Exception('API returned error code: ' . $data['code']);
            }
            
            $timings = $data['data']['timings'];
            
            return [
                'fajr' => $this->formatTime($timings['Fajr']),
                'sunrise' => $this->formatTime($timings['Sunrise']),
                'dhuhr' => $this->formatTime($timings['Dhuhr']),
                'asr' => $this->formatTime($timings['Asr']),
                'maghrib' => $this->formatTime($timings['Maghrib']),
                'isha' => $this->formatTime($timings['Isha']),
                'calculation_method' => 'ISNA'
            ];
            
        } catch (RequestException $e) {
            logger()->error('API request failed: ' . $e->getMessage());
            
            // Return default times as fallback
            return $this->getDefaultPrayerTimes();
        }
    }
    
    /**
     * Cache prayer times
     */
    private function cachePrayerTimes($city, $date, $prayerTimes)
    {
        $data = array_merge($prayerTimes, [
            'city' => $city,
            'date' => $date
        ]);
        
        // Check if already exists
        $existing = db()->prepare("SELECT id FROM {$this->table} WHERE city = ? AND date = ?");
        $existing->execute([$city, $date]);
        
        if ($existing->fetch()) {
            // Update existing
            $this->updateWhere(['city' => $city, 'date' => $date], $data);
        } else {
            // Create new
            $this->create($data);
        }
    }
    
    /**
     * Update where conditions
     */
    private function updateWhere($conditions, $data)
    {
        $sets = [];
        $values = [];
        
        foreach ($data as $column => $value) {
            if (!in_array($column, array_keys($conditions))) {
                $sets[] = "{$column} = ?";
                $values[] = $value;
            }
        }
        
        $where = [];
        foreach ($conditions as $column => $value) {
            $where[] = "{$column} = ?";
            $values[] = $value;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE " . implode(' AND ', $where);
        
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($values);
    }
    
    /**
     * Format time string
     */
    private function formatTime($time)
    {
        // Remove timezone info if present
        return explode(' ', $time)[0];
    }
    
    /**
     * Get default prayer times (fallback)
     */
    private function getDefaultPrayerTimes()
    {
        return [
            'fajr' => '05:00',
            'sunrise' => '06:15',
            'dhuhr' => '12:30',
            'asr' => '15:45',
            'maghrib' => '18:30',
            'isha' => '19:45',
            'calculation_method' => 'Default'
        ];
    }
    
    /**
     * Get prayer times for multiple days
     */
    public function getMonthlyPrayerTimes($city, $year, $month)
    {
        $stmt = $this->db()->prepare("
            SELECT * FROM {$this->table}
            WHERE city = ? 
            AND YEAR(date) = ? 
            AND MONTH(date) = ?
            ORDER BY date ASC
        ");
        
        $stmt->execute([$city, $year, $month]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get next prayer time
     */
    public function getNextPrayer($city)
    {
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');
        
        $prayerTimes = $this->getPrayerTimes($city, $today);
        if (!$prayerTimes) {
            return null;
        }
        
        $prayers = [
            'fajr' => ['name' => 'Alfajiri', 'time' => $prayerTimes['fajr']],
            'dhuhr' => ['name' => 'Adhuhuri', 'time' => $prayerTimes['dhuhr']],
            'asr' => ['name' => 'Alasiri', 'time' => $prayerTimes['asr']],
            'maghrib' => ['name' => 'Magharibi', 'time' => $prayerTimes['maghrib']],
            'isha' => ['name' => 'Isha', 'time' => $prayerTimes['isha']]
        ];
        
        foreach ($prayers as $key => $prayer) {
            if ($prayer['time'] > $currentTime) {
                return [
                    'name' => $prayer['name'],
                    'time' => $prayer['time'],
                    'remaining' => $this->getTimeRemaining($prayer['time'])
                ];
            }
        }
        
        // If all prayers passed, return tomorrow's Fajr
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $tomorrowPrayers = $this->getPrayerTimes($city, $tomorrow);
        
        return [
            'name' => 'Alfajiri',
            'time' => $tomorrowPrayers['fajr'] ?? '05:00',
            'remaining' => $this->getTimeRemaining($tomorrowPrayers['fajr'] ?? '05:00', true)
        ];
    }
    
    /**
     * Calculate time remaining until prayer
     */
    private function getTimeRemaining($prayerTime, $tomorrow = false)
    {
        $now = new \DateTime();
        $prayer = new \DateTime($prayerTime);
        
        if ($tomorrow) {
            $prayer->modify('+1 day');
        }
        
        $diff = $now->diff($prayer);
        
        if ($diff->h > 0) {
            return $diff->h . ' saa na dakika ' . $diff->i;
        } else {
            return $diff->i . ' dakika';
        }
    }
}