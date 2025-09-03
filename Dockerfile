# =============================================================================
# POCKETLEDGER - PRODUCTION DOCKERFILE FOR RENDER.COM
# =============================================================================
# Multi-stage build for optimal production deployment
# =============================================================================

# =============================================================================
# STAGE 1: Node.js Build Stage
# =============================================================================
FROM node:18-alpine AS node-builder

# Set working directory
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci --only=production

# Copy source files
COPY . .

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
    # Database drivers
    sqlite \
    # Process management
    supervisor \
    # Web server
    nginx

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
    pdo_sqlite \
    pdo_mysql \
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
# PERMISSIONS & OWNERSHIP
# =============================================================================
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# =============================================================================
# NGINX CONFIGURATION
# =============================================================================
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf

# =============================================================================
# PHP-FPM CONFIGURATION
# =============================================================================
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php.ini /usr/local/etc/php/php.ini

# =============================================================================
# SUPERVISOR CONFIGURATION
# =============================================================================
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# =============================================================================
# DOCKER ENTRYPOINT
# =============================================================================
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# =============================================================================
# HEALTH CHECK
# =============================================================================
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# =============================================================================
# EXPOSE PORTS
# =============================================================================
EXPOSE 80

# =============================================================================
# START SERVICES
# =============================================================================
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
