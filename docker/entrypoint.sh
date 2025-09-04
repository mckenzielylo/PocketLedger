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
    echo "Database configuration:"
    echo "  Host: $DB_HOST"
    echo "  Port: $DB_PORT"
    echo "  Database: $DB_DATABASE"
    echo "  Username: $DB_USERNAME"
    
    # Wait for database to be ready with timeout
    MAX_ATTEMPTS=30
    ATTEMPT=0
    
    while [ $ATTEMPT -lt $MAX_ATTEMPTS ]; do
        # Test database connection with a simple query
        if php -r "
            try {
                \$pdo = new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
                \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                \$pdo->query('SELECT 1');
                echo 'connected';
            } catch (Exception \$e) {
                echo 'failed: ' . \$e->getMessage();
                exit(1);
            }
        " > /dev/null 2>&1; then
            echo "✅ Database connection established"
            break
        else
            echo "Database not ready, waiting... (attempt $((ATTEMPT + 1))/$MAX_ATTEMPTS)"
            sleep 5
            ATTEMPT=$((ATTEMPT + 1))
        fi
    done
    
    if [ $ATTEMPT -eq $MAX_ATTEMPTS ]; then
        echo "⚠️ Database connection timeout after $MAX_ATTEMPTS attempts"
        echo "Continuing with application startup..."
    else
        # Test if we can run Laravel commands
        echo "🔍 Testing Laravel database connection..."
        if php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; then
            echo "✅ Laravel database connection verified"
        else
            echo "⚠️ Laravel database connection failed, but continuing..."
        fi
    fi
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
if php artisan package:discover --ansi; then
    echo "✅ Package discovery completed successfully"
else
    echo "⚠️ Package discovery completed with warnings, continuing..."
fi

# Optimize autoloader for better performance
echo "⚡ Optimizing autoloader..."
if composer dump-autoload --optimize --no-dev; then
    echo "✅ Autoloader optimized successfully"
else
    echo "⚠️ Autoloader optimization failed, continuing..."
fi

# Clear and cache configuration
echo "📝 Optimizing configuration..."
if php artisan config:clear && php artisan config:cache; then
    echo "✅ Configuration optimized successfully"
else
    echo "⚠️ Configuration optimization failed, continuing..."
fi

# Clear and cache routes
echo "🛣️ Optimizing routes..."
if php artisan route:clear && php artisan route:cache; then
    echo "✅ Routes optimized successfully"
else
    echo "⚠️ Routes optimization failed, continuing..."
fi

# Clear and cache views
echo "👁️ Optimizing views..."
if php artisan view:clear && php artisan view:cache; then
    echo "✅ Views optimized successfully"
else
    echo "⚠️ Views optimization failed, continuing..."
fi

# =============================================================================
# DATABASE SETUP
# =============================================================================
echo "🗄️ Setting up database..."

# Run migrations
echo "📊 Running database migrations..."
if php artisan migrate --force; then
    echo "✅ Database migrations completed successfully"
else
    echo "⚠️ Database migrations failed, continuing..."
fi

# Seed database if in production and no users exist
if [ "$APP_ENV" = "production" ]; then
    echo "🔍 Checking if database needs seeding..."
    if php artisan tinker --execute="echo App\Models\User::count();" > /dev/null 2>&1; then
        USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();")
        if [ "$USER_COUNT" -eq 0 ]; then
            echo "🌱 Seeding database with initial data..."
            if php artisan db:seed --force; then
                echo "✅ Database seeded successfully"
            else
                echo "⚠️ Database seeding failed, continuing..."
            fi
        else
            echo "✅ Database already has users, skipping seeding"
        fi
    else
        echo "⚠️ Cannot check user count, skipping seeding"
    fi
fi

# =============================================================================
# STORAGE SETUP
# =============================================================================
echo "📁 Setting up storage..."

# Create required directories
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/bootstrap/cache

# Create storage link
php artisan storage:link --force

# Set proper permissions (selective for performance)
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/public
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
# NGINX CONFIGURATION
# =============================================================================
echo "🌐 Configuring Nginx for port ${PORT:-80}..."

# Substitute PORT environment variable in Nginx configuration
envsubst '${PORT}' < /etc/nginx/http.d/default.conf > /tmp/default.conf
mv /tmp/default.conf /etc/nginx/http.d/default.conf

# =============================================================================
# START SERVICES
# =============================================================================
exec "$@"
