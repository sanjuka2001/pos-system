# =============================================================================
# POS System - Production Dockerfile
# Laravel 12 + Livewire + Vite/Tailwind
# =============================================================================

# ---- Stage 1: Build frontend assets ----
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources
RUN npm run build

# ---- Stage 2: PHP application ----
FROM php:8.2-fpm-alpine AS app

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy application code
COPY . .

# Run composer scripts (post-autoload-dump, etc.)
RUN composer dump-autoload --optimize

# Copy built frontend assets from node stage
COPY --from=node-builder /app/public/build ./public/build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# ---- Nginx Configuration ----
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# ---- PHP-FPM Configuration ----
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# ---- PHP Configuration ----
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# ---- Supervisor Configuration ----
COPY docker/supervisord.conf /etc/supervisord.conf

# ---- Entrypoint Script ----
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
