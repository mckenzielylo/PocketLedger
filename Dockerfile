# =============================================================================
# POCKETLEDGER - PRODUCTION DOCKERFILE FOR RAILWAY.APP
# =============================================================================
# Multi-stage build optimized for Railway.app deployment
# Railway-specific optimizations and configurations
# =============================================================================

# =============================================================================
# STAGE 1: Node.js Build Stage
# =============================================================================
FROM node:18-alpine AS node-builder

# Set working directory
WORKDIR /app

# Copy package files first for better caching
COPY package*.json ./

# Install dependencies with clean cache
RUN npm cache clean --force && npm install

# Copy source files
COPY . .

# Verify environment
RUN echo "Node: $(node --version)" && echo "NPM: $(npm --version)"

# Build assets
RUN npm run build

# =============================================================================
# STAGE 2: PHP Production Stage
# =============================================================================
FROM php:8.2-fpm-alpine AS production

# =============================================================================
# SYSTEM DEPENDENCIES
# =============================================================================
RUN apk add --no-cache \
    # Basic utilities
    bash \
    curl \
    git \
    unzip \
    zip \
    # Required for PHP extensions
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \

    # Process management
    supervisor \
    # Web server
    nginx \
    # Railway-specific utilities
    ca-certificates

# =============================================================================
# PHP EXTENSIONS
# =============================================================================
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    --with-xpm

RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    gd \
    zip \
    intl \
    mbstring \
    bcmath \
    opcache

# =============================================================================
# COMPOSER INSTALLATION
# =============================================================================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# =============================================================================
# APPLICATION SETUP
# =============================================================================
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (production only)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --optimize-autoloader \
    --prefer-dist

# Copy application code
COPY . .

# Copy built assets from Node.js stage
COPY --from=node-builder /app/public/build ./public/build

# Complete composer setup
RUN composer dump-autoload --optimize --no-dev

# =============================================================================
# RAILWAY-SPECIFIC CONFIGURATIONS
# =============================================================================

# Create Railway-specific directories
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/app/public

# =============================================================================
# PERMISSIONS & OWNERSHIP
# =============================================================================
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# =============================================================================
# NGINX CONFIGURATION (Railway-optimized)
# =============================================================================
COPY docker/railway-nginx.conf /etc/nginx/nginx.conf

# =============================================================================
# PHP-FPM CONFIGURATION (Railway-optimized)
# =============================================================================
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php.ini /usr/local/etc/php/php.ini

# =============================================================================
# SUPERVISOR CONFIGURATION (Railway-optimized)
# =============================================================================
COPY docker/railway-supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# =============================================================================
# RAILWAY ENTRYPOINT SCRIPT
# =============================================================================
COPY docker/railway-entrypoint.sh /usr/local/bin/railway-entrypoint.sh
RUN chmod +x /usr/local/bin/railway-entrypoint.sh

# =============================================================================
# HEALTH CHECK (Railway-compatible)
# =============================================================================
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost:$PORT/health || exit 1

# =============================================================================
# EXPOSE PORTS (Railway uses dynamic PORT)
# =============================================================================
EXPOSE $PORT

# =============================================================================
# RAILWAY STARTUP
# =============================================================================
ENTRYPOINT ["/usr/local/bin/railway-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]