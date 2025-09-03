#!/bin/bash
set -e

# =============================================================================
# POCKETLEDGER DOCKER ENTRYPOINT
# =============================================================================
# This script handles the initialization of the PocketLedger application
# =============================================================================

echo "🚀 Starting PocketLedger initialization..."

# =============================================================================
# WAIT FOR DATABASE
# =============================================================================
if [ "$DB_CONNECTION" = "mysql" ] || [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "⏳ Waiting for database connection..."
    
    # Wait for database to be ready
    until php artisan migrate:status > /dev/null 2>&1; do
        echo "Database not ready, waiting..."
        sleep 2
    done
    
    echo "✅ Database connection established"
fi

# =============================================================================
# LARAVEL APPLICATION SETUP
# =============================================================================
echo "🔧 Setting up Laravel application..."

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:temp-key-for-build" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run package discovery to ensure all packages are properly registered
echo "📦 Running package discovery..."
php artisan package:discover --ansi || echo "⚠️ Package discovery completed with warnings"

# Clear and cache configuration
echo "📝 Optimizing configuration..."
php artisan config:clear
php artisan config:cache

# Clear and cache routes
echo "🛣️ Optimizing routes..."
php artisan route:clear
php artisan route:cache

# Clear and cache views
echo "👁️ Optimizing views..."
php artisan view:clear
php artisan view:cache

# =============================================================================
# DATABASE SETUP
# =============================================================================
echo "🗄️ Setting up database..."

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Seed database if in production and no users exist
if [ "$APP_ENV" = "production" ]; then
    USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();")
    if [ "$USER_COUNT" -eq 0 ]; then
        echo "🌱 Seeding database with initial data..."
        php artisan db:seed --force
    fi
fi

# =============================================================================
# STORAGE SETUP
# =============================================================================
echo "📁 Setting up storage..."

# Create storage link
php artisan storage:link --force

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# =============================================================================
# QUEUE SETUP
# =============================================================================
echo "⚡ Setting up queues..."

# Clear failed jobs
php artisan queue:clear

# =============================================================================
# LOG DIRECTORY SETUP
# =============================================================================
echo "📋 Setting up logging..."

# Create log directories
mkdir -p /var/log/nginx
mkdir -p /var/log/php-fpm
mkdir -p /var/log/supervisor
mkdir -p /var/lib/php/session
mkdir -p /var/lib/php/wsdlcache

# Set permissions
chown -R www-data:www-data /var/lib/php/session
chown -R www-data:www-data /var/lib/php/wsdlcache
chmod -R 755 /var/lib/php/session
chmod -R 755 /var/lib/php/wsdlcache

# =============================================================================
# HEALTH CHECK
# =============================================================================
echo "🏥 Setting up health check..."

# Create health check endpoint
cat > /var/www/html/public/health.php << 'EOF'
<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'healthy',
    'timestamp' => date('c'),
    'version' => '1.0.0'
]);
EOF

# =============================================================================
# FINAL SETUP
# =============================================================================
echo "✨ Final setup..."

# Clear all caches one more time
php artisan optimize:clear
php artisan optimize

echo "🎉 PocketLedger initialization complete!"
echo "🌐 Application is ready to serve requests"

# =============================================================================
# START SERVICES
# =============================================================================
exec "$@"
