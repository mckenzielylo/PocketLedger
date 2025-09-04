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
if php artisan queue:clear; then
    echo "✅ Queue cleared successfully"
else
    echo "⚠️ Queue clearing failed, continuing..."
fi

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

# Check if assets were built correctly
echo "📦 Checking frontend assets..."
if [ -d "/var/www/html/public/build" ]; then
    echo "✅ Frontend assets found in /var/www/html/public/build"
    ls -la /var/www/html/public/build/
    
    # Check for Vite manifest
    if [ -f "/var/www/html/public/build/manifest.json" ]; then
        echo "✅ Vite manifest found"
        echo "📋 Manifest content:"
        cat /var/www/html/public/build/manifest.json
    else
        echo "❌ Vite manifest not found"
    fi
else
    echo "❌ Frontend assets not found in /var/www/html/public/build"
    echo "📋 Contents of public directory:"
    ls -la /var/www/html/public/
    
    # Check if there are any build artifacts
    echo "📋 Checking for any build artifacts:"
    find /var/www/html -name "*.css" -o -name "*.js" | head -10
    
    # Try to rebuild assets if they're missing
    echo "🔧 Attempting to rebuild frontend assets..."
    if npm run build; then
        echo "✅ Frontend assets rebuilt successfully"
        ls -la /var/www/html/public/build/ 2>/dev/null || echo "Build directory still not found"
    else
        echo "❌ Failed to rebuild frontend assets"
    fi
fi

# Quick optimization (skip if it fails)
echo "⚡ Quick application optimization..."
if php artisan optimize --quiet; then
    echo "✅ Application optimized successfully"
else
    echo "⚠️ Application optimization skipped, continuing..."
fi

echo "🎉 PocketLedger initialization complete!"
echo "🌐 Application is ready to serve requests"

# =============================================================================
# NGINX CONFIGURATION
# =============================================================================
echo "🌐 Configuring Nginx for port ${PORT:-80}..."

# Create Nginx configuration with the correct port
PORT_VALUE=${PORT:-80}
echo "🔧 Setting Nginx to listen on port $PORT_VALUE"

# Create a simplified Nginx configuration file with the correct port
cat > /etc/nginx/http.d/default.conf << EOF
server {
    listen $PORT_VALUE;
    listen [::]:$PORT_VALUE;
    server_name _;
    root /var/www/html/public;
    index index.php index.html index.htm;

    # Security
    server_tokens off;

    # Logging
    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    # Client settings
    client_max_body_size 20M;

    # Gzip
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript;

    # Handle Laravel routes
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # PHP-FPM configuration
    location ~ \.php\$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        
        # FastCGI settings
        fastcgi_connect_timeout 60s;
        fastcgi_send_timeout 60s;
        fastcgi_read_timeout 60s;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
        fastcgi_temp_file_write_size 256k;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf|svg)\$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Deny access to sensitive files
    location ~* \.(env|log|htaccess|htpasswd|ini|sh|sql|conf)\$ {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Health check endpoint
    location /health {
        access_log off;
        return 200 "healthy\n";
        add_header Content-Type text/plain;
    }
}
EOF

# Test Nginx configuration
echo "🧪 Testing Nginx configuration..."
if nginx -t 2>&1; then
    echo "✅ Nginx configuration is valid"
else
    echo "❌ Nginx configuration test failed"
    echo "📋 Nginx error details:"
    nginx -t 2>&1
    echo "📋 Nginx configuration content:"
    cat /etc/nginx/http.d/default.conf
    echo "📋 Available Nginx modules:"
    nginx -V 2>&1
    echo "📋 Nginx error log:"
    cat /var/log/nginx/error.log 2>/dev/null || echo "No error log found"
fi

# Check if Nginx can start
echo "🚀 Testing Nginx startup..."
if timeout 10 nginx -g "daemon off;" & then
    NGINX_PID=$!
    sleep 2
    if kill -0 $NGINX_PID 2>/dev/null; then
        echo "✅ Nginx started successfully"
        kill $NGINX_PID
    else
        echo "❌ Nginx failed to start"
        echo "📋 Nginx error log after startup attempt:"
        cat /var/log/nginx/error.log 2>/dev/null || echo "No error log found"
    fi
else
    echo "❌ Nginx startup test failed"
fi

# =============================================================================
# START SERVICES
# =============================================================================
exec "$@"
