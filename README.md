# PocketLedger - Personal Finance Manager

A production-ready, mobile-first personal finance web application built with Laravel 11, Tailwind CSS, and Alpine.js. Track income, expenses, debts, assets, and budgets with a beautiful, responsive interface.

## Features

- 💰 **Transaction Management**: Add, edit, and delete income, expense, and transfer transactions
- 🏦 **Account Management**: Multiple accounts (cash, bank, e-wallet) with balance tracking
- 📊 **Categories**: Customizable income and expense categories with color coding
- 🔄 **Recurring Transactions**: Set up automatic recurring income/expenses
- 💳 **Debt Tracking**: Monitor debts with payment history and balance tracking
- 🏠 **Asset Management**: Track assets with depreciation calculations
- 📈 **Budget Planning**: Monthly budgets with category limits and progress tracking
- 📱 **Mobile-First Design**: Responsive design optimized for mobile devices
- 🌙 **Dark Mode**: Toggle between light and dark themes
- 📊 **Charts & Reports**: Visual insights with Chart.js integration
- 🔌 **PWA Support**: Install as a mobile app with offline capabilities
- 🌍 **Internationalization**: Support for multiple languages and currencies

## Tech Stack

- **Backend**: PHP 8.3, Laravel 11, MySQL/SQLite
- **Frontend**: Tailwind CSS, Alpine.js, Chart.js
- **Authentication**: Laravel Breeze with email verification
- **Testing**: Pest PHP testing framework
- **Code Quality**: PHP CS Fixer, Larastan static analysis
- **PWA**: Service Worker, Web App Manifest

## Requirements

- PHP 8.3 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0+ or SQLite 3
- Web server (Apache/Nginx)

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd pocketledger
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node.js dependencies

```bash
npm install
```

### 4. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file with your database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pocketledger
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run migrations and seeders

```bash
php artisan migrate --seed
```

### 6. Build assets

```bash
npm run build
```

### 7. Start the development server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Login

After running the seeders, you can log in with:

- **Email**: `demo@pocketledger.com`
- **Password**: `password`

## Usage

### Dashboard

The main dashboard shows:
- Total balance across all accounts
- Recent transactions
- Account summaries
- Budget progress
- Quick action buttons

### Adding Transactions

1. Click "Add Transaction" button
2. Choose transaction type (Income/Expense/Transfer)
3. Select account and category
4. Enter amount and details
5. Save transaction

### Managing Accounts

- Create multiple accounts (cash, bank, e-wallet)
- Set starting balances
- Monitor current balances
- Archive inactive accounts

### Budget Planning

- Set monthly budget limits
- Configure category-specific limits
- Track spending progress
- Receive warnings at 80% usage

### Reports & Analytics

- **Cashflow Chart**: Monthly income vs expenses
- **Category Breakdown**: Pie chart of spending by category
- **Net Worth Trend**: Line chart showing net worth over time

## API Endpoints

### Reports API

```bash
# Cashflow data
GET /api/reports/cashflow?from=2024-01&to=2024-12

# Category breakdown
GET /api/reports/category-breakdown?month=2024-08

# Net worth data
GET /api/reports/net-worth?from=2024-01&to=2024-12

# Sync transactions for offline use
GET /api/sync/transactions?since=2024-08-01T00:00:00Z
```

## PWA Features

### Installation

- Visit the app in a supported browser
- Click "Install" when prompted
- App will be added to home screen

### Offline Support

- App shell cached for offline access
- Last 30 days of data cached
- Background sync when connection restored
- Offline transaction creation

### Service Worker

- Caches essential resources
- Handles offline scenarios
- Background sync capabilities
- Push notification support

## Testing

### Run tests

```bash
# Run all tests
php artisan test

# Run with Pest
./vendor/bin/pest

# Run specific test file
./vendor/bin/pest tests/Feature/TransactionTest.php
```

### Code quality

```bash
# PHP CS Fixer
./vendor/bin/pint

# Larastan static analysis
./vendor/bin/phpstan analyse
```

## Deployment

### Production Server Setup

#### 1. Server Requirements

- Ubuntu 20.04+ or CentOS 8+
- PHP 8.3 with extensions
- MySQL 8.0+ or PostgreSQL
- Nginx or Apache
- SSL certificate

#### 2. Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl

# Install MySQL
sudo apt install mysql-server

# Install Nginx
sudo apt install nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### 3. Configure Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    root /var/www/pocketledger/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 4. Deploy Application

```bash
# Clone repository
cd /var/www
sudo git clone <repository-url> pocketledger
sudo chown -R www-data:www-data pocketledger

# Install dependencies
cd pocketledger
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Configure environment
cp .env.example .env
php artisan key:generate
# Edit .env with production settings

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 5. Queue Worker (Optional)

```bash
# Install Supervisor
sudo apt install supervisor

# Create configuration
sudo nano /etc/supervisor/conf.d/pocketledger.conf
```

```ini
[program:pocketledger-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/pocketledger/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/pocketledger/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start pocketledger-worker:*
```

### SSL with Let's Encrypt

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Performance Optimization

```bash
# Install Redis for caching
sudo apt install redis-server

# Configure Laravel to use Redis
# Update .env file
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Install OPcache
sudo apt install php8.3-opcache

# Configure OPcache in php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

## Security

### Best Practices

- Use HTTPS in production
- Regular security updates
- Database backups
- Rate limiting on API endpoints
- Input validation and sanitization
- CSRF protection enabled
- XSS protection headers

### Environment Variables

```env
# Security
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pocketledger
DB_USERNAME=secure_username
DB_PASSWORD=strong_password

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For support and questions:
- Create an issue on GitHub
- Check the documentation
- Review the code examples

## Changelog

### v1.0.0
- Initial release
- Core transaction management
- Account and category management
- Budget planning
- Debt and asset tracking
- PWA support
- Mobile-first responsive design
- Dark mode support
- Charts and reporting
- Multi-language support
