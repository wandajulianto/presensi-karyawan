#!/bin/bash

# Azure App Service startup script untuk Laravel
echo "Starting Laravel application..."

# Navigate to application directory
cd /home/site/wwwroot

# Generate app key if not exists
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache configurations
echo "Optimizing Laravel for production..."
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link || true

# Set proper permissions
echo "Setting file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "Laravel application is ready!"

# Start PHP-FPM (this is handled by Azure App Service automatically)
# Just ensure the script exits successfully
exit 0 