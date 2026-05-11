#!/bin/sh
set -e

echo "🚀 Starting POS System..."

# Ensure storage directories exist with correct permissions
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY not set. Generating one..."
    php artisan key:generate --force
fi

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "📦 Running database migrations..."
php artisan migrate --force

echo "✅ POS System ready!"

# Execute the main command (supervisord)
exec "$@"
