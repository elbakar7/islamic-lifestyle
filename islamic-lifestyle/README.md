# Islamic Lifestyle Platform

A comprehensive Islamic web platform designed to solve Islamic needs for the Tanzanian Muslim society.

## Features

✅ **Prayer Times** - Accurate prayer times for all Tanzanian cities with API integration  
✅ **Qur'an Reader** - Read Qur'an with Swahili translation (Tafsir)  
✅ **Hadith Collections** - Browse Sahih Bukhari, Muslim, Tirmidhi collections  
✅ **Islamic Q&A (Fatwa Bank)** - Ask questions and get answers from scholars  
✅ **Zakat Calculator** - Calculate Zakat based on Islamic rulings  
✅ **Islamic Inheritance Calculator** - Calculate inheritance shares per Fiqh rules  
✅ **Halal Business Directory** - Find halal businesses in Tanzania  
✅ **Donation System** - Accept Sadaqah, Zakat, Waqf with payment integration  
✅ **User Account System** - Role-based access (Admin, Scholar, User)  
✅ **Islamic Calendar** - Hijri calendar with Islamic events  
✅ **Islamic Blog/Articles** - Educational content from scholars  

## Technology Stack

- **Backend**: PHP 8+ with MVC architecture
- **Database**: MySQL
- **Frontend**: TailwindCSS, Font Awesome
- **APIs**: AlAdhan Prayer Times API, Qur'an API
- **Payment**: Flutterwave & M-Pesa integration ready

## Installation

### Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Composer
- Apache/Nginx web server

### Setup Steps

1. **Clone the repository**
```bash
cd /workspace/islamic-lifestyle
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
```
Edit `.env` file with your database credentials and API keys.

4. **Create database**
```bash
mysql -u root -p < database/schema.sql
```

5. **Set up web server**

For Apache, ensure mod_rewrite is enabled. The `.htaccess` file is already configured.

For Nginx, use this configuration:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

6. **Set permissions**
```bash
chmod -R 755 public
chmod -R 777 storage
```

## Default Login

- **Username**: admin
- **Email**: admin@islamiclifestyle.tz  
- **Password**: Admin@123

## Project Structure

```
islamic-lifestyle/
├── app/
│   ├── controllers/     # Application controllers
│   ├── models/          # Database models
│   └── views/           # View templates
├── config/              # Configuration files
├── database/            # Database schema and migrations
├── includes/            # Bootstrap and helper functions
├── public/              # Public web root
│   ├── assets/          # CSS, JS, images
│   └── index.php        # Main entry point
└── storage/             # Logs and uploads
```

## API Configuration

### Prayer Times API
The platform uses AlAdhan API for prayer times. No API key required.

### Payment Integration

#### Flutterwave
Add your Flutterwave credentials in `.env`:
```
FLUTTERWAVE_PUBLIC_KEY=your_public_key
FLUTTERWAVE_SECRET_KEY=your_secret_key
```

#### M-Pesa
Add your M-Pesa credentials in `.env`:
```
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_SHORTCODE=your_shortcode
MPESA_PASSKEY=your_passkey
```

## Usage

### For Users
1. Register for an account
2. Set your location for accurate prayer times
3. Access all Islamic features from the dashboard

### For Scholars
1. Login with scholar credentials
2. Answer fatwa questions from the admin panel
3. Publish Islamic articles

### For Admins
1. Access admin panel at `/admin`
2. Manage users, fatwas, businesses, donations
3. Configure system settings

## Security Features

- Password hashing with bcrypt
- CSRF protection on all forms
- Prepared statements for database queries
- Input sanitization and validation
- Session security with secure cookies

## Development

### Adding New Features

1. Create controller in `app/controllers/`
2. Create model in `app/models/`
3. Create views in `app/views/`
4. Add routes in `public/index.php`

### Database Changes

Add new migrations to `database/` folder and update schema.

## Support

For issues or questions, contact: info@islamiclifestyle.tz

## License

This project is developed for the Tanzanian Muslim community.

---

Built with ❤️ for the Tanzanian Muslim Community