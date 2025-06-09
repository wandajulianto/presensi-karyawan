#!/bin/bash

# Azure App Service Linux startup script untuk Laravel
echo "Starting Laravel application on Azure App Service Linux..."

# Set working directory
cd /home/site/wwwroot

# Create .htaccess if not exists
if [ ! -f "public/.htaccess" ]; then
    echo "Creating .htaccess file..."
    cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF
fi

# Set permissions
echo "Setting file permissions..."
chmod -R 755 storage bootstrap/cache public
chown -R www-data:www-data storage bootstrap/cache

# Clear and optimize Laravel
echo "Optimizing Laravel..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

# Cache configurations for production
echo "Caching configurations..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link || true

echo "Laravel application ready!"

# Start Apache/Nginx (handled by Azure App Service)
# Just ensure script exits successfully
exit 0 