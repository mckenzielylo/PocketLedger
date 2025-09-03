#!/bin/bash
set -e

# =============================================================================
# RAILWAY.APP ENTRYPOINT SCRIPT FOR POCKETLEDGER
# =============================================================================
# This script handles the startup process for Railway.app deployment
# =============================================================================

echo "🚀 Starting PocketLedger on Railway.app..."

# =============================================================================
# ENVIRONMENT VALIDATION
# =============================================================================
echo "📋 Validating environment variables..."

# Check required environment variables
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY not set, generating new one..."
    php artisan key:generate --force
fi

if [ -z "$DB_CONNECTION" ]; then
    echo "⚠️  DB_CONNECTION not set, defaulting to pgsql..."
    export DB_CONNECTION=pgsql
fi

# =============================================================================
# RAILWAY-SPECIFIC CONFIGURATIONS
# =============================================================================
echo "🔧 Configuring Railway-specific settings..."

# Set Railway-specific PHP settings
export PHP_CLI_SERVER_WORKERS=4
export BCRYPT_ROUNDS=12

# Configure for Railway's ephemeral filesystem
export SESSION_DRIVER=file
export CACHE_STORE=file
export QUEUE_CONNECTION=sync

# =============================================================================
# DATABASE SETUP
# =============================================================================
echo "🗄️  Setting up database..."

# Wait for database to be ready (Railway-specific)
if [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "⏳ Waiting for PostgreSQL to be ready..."
    until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME"; do
        echo "⏳ PostgreSQL is unavailable - sleeping..."
        sleep 2
    done
    echo "✅ PostgreSQL is ready!"
fi

# Run database migrations
echo "🔄 Running database migrations..."
php artisan migrate --force

# =============================================================================
# CACHE OPTIMIZATION
# =============================================================================
echo "⚡ Optimizing application cache..."

# Clear and rebuild caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache configurations for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# =============================================================================
# STORAGE SETUP
# =============================================================================
echo "📁 Setting up storage..."

# Create storage link
php artisan storage:link

# Ensure storage directories exist
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/app/public

# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# =============================================================================
# RAILWAY PORT CONFIGURATION
# =============================================================================
echo "🌐 Configuring Railway port..."

# Railway provides PORT environment variable
if [ -z "$PORT" ]; then
    export PORT=80
fi

# Update Nginx configuration with Railway port
sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf

# =============================================================================
# SEEDING (Optional)
# =============================================================================
if [ "$RAILWAY_ENVIRONMENT" = "production" ] && [ "$SEED_DATABASE" = "true" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force
fi

# =============================================================================
# STARTUP COMPLETE
# =============================================================================
echo "✅ PocketLedger is ready to start!"
echo "🌐 Application will be available on port $PORT"
echo "📊 Health check available at /health"

# Execute the main command
exec "$@"
