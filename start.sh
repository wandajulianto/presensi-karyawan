#!/bin/bash

# Exit on any error
set -e

echo "Starting Laravel application..."

# Wait for database to be ready (if using external DB)
echo "Waiting for database connection..."
until php artisan migrate:status &>/dev/null; do
    echo "Database not ready, retrying in 2 seconds..."
    sleep 2
done

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache configurations for production
echo "Optimizing Laravel for production..."
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link || true

# Set proper permissions
echo "Setting file permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start supervisor (which will manage nginx and php-fpm)
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
