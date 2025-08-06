<?php
/**
 * Application Configuration
 * 
 * This file contains all the main configuration settings for the Islamic Lifestyle platform
 */

return [
    // Application settings
    'name' => env('APP_NAME', 'Islamic Lifestyle'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'Africa/Dar_es_Salaam'),
    
    // Supported languages
    'languages' => [
        'en' => 'English',
        'sw' => 'Kiswahili',
        'ar' => 'العربية'
    ],
    
    // Default language
    'default_language' => 'sw',
    
    // Tanzanian regions for location selection
    'regions' => [
        'dar-es-salaam' => 'Dar es Salaam',
        'zanzibar' => 'Zanzibar',
        'arusha' => 'Arusha',
        'mwanza' => 'Mwanza',
        'dodoma' => 'Dodoma',
        'mbeya' => 'Mbeya',
        'morogoro' => 'Morogoro',
        'tanga' => 'Tanga',
        'kilimanjaro' => 'Kilimanjaro',
        'tabora' => 'Tabora',
        'iringa' => 'Iringa',
        'mtwara' => 'Mtwara',
        'kigoma' => 'Kigoma',
        'pwani' => 'Pwani',
        'ruvuma' => 'Ruvuma',
        'shinyanga' => 'Shinyanga',
        'kagera' => 'Kagera',
        'manyara' => 'Manyara',
        'singida' => 'Singida',
        'rukwa' => 'Rukwa',
        'katavi' => 'Katavi',
        'simiyu' => 'Simiyu',
        'geita' => 'Geita',
        'njombe' => 'Njombe',
        'songwe' => 'Songwe'
    ],
    
    // Major cities with coordinates for prayer times
    'cities' => [
        'dar-es-salaam' => ['name' => 'Dar es Salaam', 'lat' => -6.7924, 'lng' => 39.2083],
        'zanzibar' => ['name' => 'Zanzibar', 'lat' => -6.1659, 'lng' => 39.2026],
        'arusha' => ['name' => 'Arusha', 'lat' => -3.3869, 'lng' => 36.6830],
        'mwanza' => ['name' => 'Mwanza', 'lat' => -2.5164, 'lng' => 32.9175],
        'dodoma' => ['name' => 'Dodoma', 'lat' => -6.1630, 'lng' => 35.7516],
        'mbeya' => ['name' => 'Mbeya', 'lat' => -8.9094, 'lng' => 33.4608],
        'morogoro' => ['name' => 'Morogoro', 'lat' => -6.8278, 'lng' => 37.6591],
        'tanga' => ['name' => 'Tanga', 'lat' => -5.0689, 'lng' => 39.0988],
        'moshi' => ['name' => 'Moshi', 'lat' => -3.3519, 'lng' => 37.3440],
        'tabora' => ['name' => 'Tabora', 'lat' => -5.0163, 'lng' => 32.8266]
    ],
    
    // Prayer calculation methods
    'prayer_methods' => [
        'MWL' => 'Muslim World League',
        'ISNA' => 'Islamic Society of North America',
        'Egypt' => 'Egyptian General Authority of Survey',
        'Makkah' => 'Umm al-Qura University, Makkah',
        'Karachi' => 'University of Islamic Sciences, Karachi',
        'Tehran' => 'Institute of Geophysics, University of Tehran',
        'Jafari' => 'Shia Ithna Ashari (Jafari)'
    ],
    
    // Business categories
    'business_categories' => [
        'restaurant' => 'Restaurants & Food',
        'grocery' => 'Grocery & Supermarkets',
        'butcher' => 'Halal Butchers',
        'fashion' => 'Islamic Fashion',
        'education' => 'Islamic Schools',
        'finance' => 'Islamic Finance',
        'travel' => 'Hajj & Umrah Services',
        'books' => 'Islamic Books & Media',
        'mosque' => 'Mosques & Prayer Spaces',
        'health' => 'Healthcare Services',
        'other' => 'Other Services'
    ],
    
    // Fatwa categories
    'fatwa_categories' => [
        'worship' => 'Worship & Prayer',
        'fasting' => 'Fasting & Ramadan',
        'zakat' => 'Zakat & Charity',
        'hajj' => 'Hajj & Umrah',
        'marriage' => 'Marriage & Family',
        'business' => 'Business & Finance',
        'food' => 'Food & Drink',
        'clothing' => 'Clothing & Appearance',
        'social' => 'Social Issues',
        'women' => 'Women Issues',
        'youth' => 'Youth Issues',
        'technology' => 'Technology & Modern Life',
        'other' => 'Other'
    ],
    
    // Article categories
    'article_categories' => [
        'faith' => 'Faith & Belief',
        'worship' => 'Worship & Spirituality',
        'quran' => 'Quran & Tafsir',
        'hadith' => 'Hadith & Sunnah',
        'family' => 'Family & Marriage',
        'youth' => 'Youth & Education',
        'women' => 'Women in Islam',
        'finance' => 'Islamic Finance',
        'health' => 'Health & Wellness',
        'society' => 'Society & Culture',
        'history' => 'Islamic History',
        'contemporary' => 'Contemporary Issues'
    ],
    
    // Zakat rates
    'zakat' => [
        'rate' => 0.025, // 2.5%
        'nisab_gold_grams' => 85,
        'nisab_silver_grams' => 595,
        'livestock' => [
            'camel' => ['nisab' => 5, 'rate' => 'special'],
            'cattle' => ['nisab' => 30, 'rate' => 'special'],
            'sheep_goat' => ['nisab' => 40, 'rate' => 'special']
        ]
    ],
    
    // Upload settings
    'upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'allowed_documents' => ['pdf', 'doc', 'docx']
    ],
    
    // Session settings
    'session' => [
        'lifetime' => env('SESSION_LIFETIME', 120), // minutes
        'secure' => env('SESSION_SECURE_COOKIE', true),
        'http_only' => true,
        'same_site' => 'lax'
    ],
    
    // Pagination
    'pagination' => [
        'per_page' => 20,
        'max_per_page' => 100
    ]
];