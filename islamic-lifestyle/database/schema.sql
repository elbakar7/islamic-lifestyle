-- Islamic Lifestyle Platform Database Schema
-- Version: 1.0
-- Author: Islamic Lifestyle Development Team

CREATE DATABASE IF NOT EXISTS islamic_lifestyle CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE islamic_lifestyle;

-- Users table with role-based access
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    region VARCHAR(50),
    role ENUM('user', 'scholar', 'admin') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(100),
    reset_token VARCHAR(100),
    reset_token_expires DATETIME,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB;

-- Prayer times cache
CREATE TABLE prayer_times (
    id INT PRIMARY KEY AUTO_INCREMENT,
    city VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    fajr TIME NOT NULL,
    sunrise TIME NOT NULL,
    dhuhr TIME NOT NULL,
    asr TIME NOT NULL,
    maghrib TIME NOT NULL,
    isha TIME NOT NULL,
    calculation_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_city_date (city, date),
    INDEX idx_date (date)
) ENGINE=InnoDB;

-- Quran verses with translations
CREATE TABLE quran_verses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    surah_number INT NOT NULL,
    ayah_number INT NOT NULL,
    arabic_text TEXT NOT NULL,
    swahili_translation TEXT,
    english_translation TEXT,
    audio_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_verse (surah_number, ayah_number),
    INDEX idx_surah (surah_number)
) ENGINE=InnoDB;

-- Surah information
CREATE TABLE surahs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    surah_number INT UNIQUE NOT NULL,
    arabic_name VARCHAR(100) NOT NULL,
    english_name VARCHAR(100) NOT NULL,
    swahili_name VARCHAR(100),
    revelation_type ENUM('Meccan', 'Medinan'),
    total_verses INT NOT NULL,
    INDEX idx_number (surah_number)
) ENGINE=InnoDB;

-- Hadith collections
CREATE TABLE hadiths (
    id INT PRIMARY KEY AUTO_INCREMENT,
    collection VARCHAR(50) NOT NULL,
    book_number INT,
    hadith_number VARCHAR(20) NOT NULL,
    arabic_text TEXT,
    english_text TEXT NOT NULL,
    swahili_text TEXT,
    narrator VARCHAR(200),
    grade VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_collection (collection),
    INDEX idx_number (hadith_number),
    FULLTEXT idx_fulltext (english_text, swahili_text)
) ENGINE=InnoDB;

-- Fatwa questions and answers
CREATE TABLE fatwas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    question_title VARCHAR(200) NOT NULL,
    question_text TEXT NOT NULL,
    answer_text TEXT,
    answered_by INT,
    category VARCHAR(50),
    status ENUM('pending', 'answered', 'published', 'rejected') DEFAULT 'pending',
    views INT DEFAULT 0,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    answered_at DATETIME,
    published_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (answered_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_category (category),
    FULLTEXT idx_search (question_title, question_text, answer_text)
) ENGINE=InnoDB;

-- Zakat calculations history
CREATE TABLE zakat_calculations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    calculation_type VARCHAR(50) NOT NULL,
    nisab_amount DECIMAL(15,2),
    total_wealth DECIMAL(15,2) NOT NULL,
    zakatable_amount DECIMAL(15,2) NOT NULL,
    zakat_due DECIMAL(15,2) NOT NULL,
    calculation_details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- Inheritance calculations
CREATE TABLE inheritance_calculations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    deceased_gender ENUM('male', 'female') NOT NULL,
    estate_value DECIMAL(15,2),
    heirs_data JSON NOT NULL,
    calculation_result JSON NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- Halal businesses directory
CREATE TABLE businesses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    business_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    address TEXT,
    city VARCHAR(50) NOT NULL,
    region VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    whatsapp VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(200),
    google_maps_link VARCHAR(500),
    halal_certified BOOLEAN DEFAULT FALSE,
    certification_details VARCHAR(200),
    is_verified BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    added_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_category (category),
    INDEX idx_region (region),
    FULLTEXT idx_search (business_name, description)
) ENGINE=InnoDB;

-- Donations
CREATE TABLE donations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    donation_type ENUM('zakat', 'sadaqah', 'waqf', 'general') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'TZS',
    payment_method ENUM('mpesa', 'flutterwave', 'bank', 'cash') NOT NULL,
    transaction_reference VARCHAR(100) UNIQUE,
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    donor_name VARCHAR(100),
    donor_email VARCHAR(100),
    donor_phone VARCHAR(20),
    purpose TEXT,
    receipt_number VARCHAR(50),
    payment_details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (payment_status),
    INDEX idx_type (donation_type),
    INDEX idx_reference (transaction_reference)
) ENGINE=InnoDB;

-- Islamic calendar events
CREATE TABLE islamic_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_name VARCHAR(100) NOT NULL,
    event_type VARCHAR(50),
    hijri_date VARCHAR(20),
    gregorian_date DATE,
    description TEXT,
    is_recurring BOOLEAN DEFAULT FALSE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_date (gregorian_date)
) ENGINE=InnoDB;

-- Blog articles
CREATE TABLE articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    category VARCHAR(50) NOT NULL,
    author_id INT NOT NULL,
    featured_image VARCHAR(255),
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    views INT DEFAULT 0,
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_slug (slug),
    FULLTEXT idx_search (title, content, excerpt)
) ENGINE=InnoDB;

-- Article comments
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    article_id INT NOT NULL,
    user_id INT NOT NULL,
    parent_id INT,
    comment_text TEXT NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
    INDEX idx_article (article_id),
    INDEX idx_approved (is_approved)
) ENGINE=InnoDB;

-- User sessions
CREATE TABLE user_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_token VARCHAR(100) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (session_token),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB;

-- Activity logs
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    module VARCHAR(50),
    details JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- System settings
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type VARCHAR(50),
    description TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Insert default admin user (password: Admin@123)
INSERT INTO users (username, email, password_hash, full_name, role, is_active, email_verified) 
VALUES ('admin', 'admin@islamiclifestyle.tz', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', TRUE, TRUE);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'Islamic Lifestyle', 'general', 'Website name'),
('site_tagline', 'Your Complete Islamic Companion', 'general', 'Website tagline'),
('default_prayer_method', 'MuslimWorldLeague', 'prayer', 'Default calculation method for prayer times'),
('zakat_nisab_gold', '85', 'zakat', 'Nisab threshold in grams of gold'),
('zakat_nisab_silver', '595', 'zakat', 'Nisab threshold in grams of silver');

-- Insert sample Islamic events
INSERT INTO islamic_events (event_name, event_type, hijri_date, description, is_recurring) VALUES
('Ramadan', 'month', '1 Ramadan', 'Holy month of fasting', TRUE),
('Eid al-Fitr', 'celebration', '1 Shawwal', 'Festival of breaking the fast', TRUE),
('Eid al-Adha', 'celebration', '10 Dhul Hijjah', 'Festival of sacrifice', TRUE),
('Mawlid an-Nabi', 'celebration', '12 Rabi al-Awwal', 'Birthday of Prophet Muhammad (PBUH)', TRUE),
('Isra and Miraj', 'event', '27 Rajab', 'Night journey of Prophet Muhammad (PBUH)', TRUE);

-- Create indexes for performance
CREATE INDEX idx_users_login ON users(email, password_hash);
CREATE INDEX idx_donations_user_date ON donations(user_id, created_at);
CREATE INDEX idx_articles_author_status ON articles(author_id, status);