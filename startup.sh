#!/bin/bash
cd /home/site/wwwroot
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan storage:link
cp .env.production .env
php -S 0.0.0.0:8080 -t public
