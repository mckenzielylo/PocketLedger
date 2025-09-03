# 🏦 PocketLedger Setup Guide

A comprehensive guide to setting up PocketLedger, your personal finance management application.

## 📋 Prerequisites

Before setting up PocketLedger, ensure you have the following installed:

- **PHP 8.1+** with extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **Composer** (PHP dependency manager)
- **Node.js 16+** and **npm** (for frontend assets)
- **SQLite** (default) or **MySQL/PostgreSQL** (for production)

## 🚀 Quick Setup

### 1. Clone and Install Dependencies

```bash
# Clone the repository (if not already done)
git clone <your-repo-url>
cd pocketledger

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Setup

```bash
# Create SQLite database file (if using SQLite)
touch database/database.sqlite

# Run database migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 4. Storage and Assets

```bash
# Create storage link for file uploads
php artisan storage:link

# Build frontend assets
npm run build
```

### 5. Start the Application

```bash
# Start the development server
php artisan serve
```

Visit `http://localhost:8000` to access PocketLedger!

## 🔧 Detailed Configuration

### Environment Variables

The `.env.example` file contains comprehensive configuration options. Key settings include:

#### Application Settings
```env
APP_NAME="PocketLedger"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

#### Database Configuration
```env
# SQLite (default for development)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# MySQL (for production)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=pocketledger
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

#### Mail Configuration
```env
# Development (logs to file)
MAIL_MAILER=log

# Production (SMTP)
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=your_email@gmail.com
# MAIL_PASSWORD=your_app_password
```

### Database Options

#### SQLite (Recommended for Development)
- **Pros**: No server setup, file-based, perfect for development
- **Cons**: Limited concurrent connections, not ideal for production

#### MySQL (Recommended for Production)
- **Pros**: Robust, scalable, excellent performance
- **Cons**: Requires server setup

#### PostgreSQL (Alternative for Production)
- **Pros**: Advanced features, excellent for complex queries
- **Cons**: Requires server setup

## 🎯 Features Overview

PocketLedger includes the following features:

### 💰 Financial Management
- **Multi-Currency Support**: USD, EUR, GBP, JPY, and 14+ other currencies
- **Account Management**: Multiple bank accounts, credit cards, cash accounts
- **Transaction Tracking**: Income, expenses, and transfers
- **Category Management**: Customizable expense categories with icons
- **Receipt Upload**: Attach receipts to transactions

### 📊 Budgeting & Analytics
- **Budget Creation**: Set monthly budgets by category
- **Progress Tracking**: Visual progress bars with animations
- **Spending Analysis**: Track spending patterns and trends
- **Multi-Currency Budgets**: Budget in different currencies

### 🏦 Debt Management
- **Debt Tracking**: Credit cards, loans, mortgages
- **Payment Scheduling**: Track debt payments and progress
- **Interest Calculation**: Monitor interest accumulation

### 💎 Asset Management
- **Asset Tracking**: Real estate, investments, valuables
- **Value Monitoring**: Track asset values over time

### 🔄 Advanced Features
- **Currency Conversion**: Real-time exchange rates
- **Recurring Transactions**: Automated transaction scheduling
- **Data Export**: Export financial data
- **Dark Mode**: Beautiful dark theme support

## 🛠️ Development Commands

### Database Management
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Seed specific seeder
php artisan db:seed --class=UserSeeder
```

### Asset Management
```bash
# Build assets for production
npm run build

# Watch for changes during development
npm run dev

# Install new packages
npm install package-name
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=TransactionTest
```

## 🚀 Production Deployment

### Environment Setup
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
LOG_LEVEL=error
SESSION_SECURE_COOKIE=true
```

### Database Migration
```bash
# Use MySQL or PostgreSQL for production
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=pocketledger
DB_USERNAME=your-username
DB_PASSWORD=your-secure-password
```

### File Storage
```env
# Use S3 for file storage in production
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_BUCKET=your-bucket-name
```

### Security Considerations
- Set strong database passwords
- Use HTTPS in production
- Configure proper file permissions
- Set up regular database backups
- Use environment-specific configurations

## 🔍 Troubleshooting

### Common Issues

#### Database Connection Errors
```bash
# Check database file exists (SQLite)
ls -la database/database.sqlite

# Check database permissions
chmod 664 database/database.sqlite
```

#### Storage Link Issues
```bash
# Recreate storage link
php artisan storage:link
```

#### Asset Build Errors
```bash
# Clear npm cache
npm cache clean --force

# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install
```

#### Permission Issues
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Log Files
Check these files for error details:
- `storage/logs/laravel.log` - Application logs
- `storage/logs/laravel-{date}.log` - Daily logs

## 📚 Additional Resources

### Laravel Documentation
- [Laravel 11.x Documentation](https://laravel.com/docs/11.x)
- [Laravel Eloquent ORM](https://laravel.com/docs/11.x/eloquent)
- [Laravel Blade Templates](https://laravel.com/docs/11.x/blade)

### Frontend Technologies
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)
- [FontAwesome Icons](https://fontawesome.com/icons)

### Database Resources
- [SQLite Documentation](https://www.sqlite.org/docs.html)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new features
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

**Happy budgeting with PocketLedger! 🎉**
