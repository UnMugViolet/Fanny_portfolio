#!/bin/bash
set -e

echo "Starting Laravel application..."

# Ensure Laravel storage directories exist and have proper permissions
echo "Setting up Laravel storage directories and permissions..."

# Create directories if they don't exist (in case of clean volume)
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Set ownership and permissions
echo "Setting ownership to www-data..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "Setting permissions..."
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Ensure Laravel can write to specific directories
chmod -R 777 /var/www/html/storage/framework/cache
chmod -R 777 /var/www/html/storage/framework/sessions  
chmod -R 777 /var/www/html/storage/framework/views
chmod -R 777 /var/www/html/storage/logs
chmod -R 777 /var/www/html/bootstrap/cache

echo "Storage permissions set successfully!"

# Switch to www user for Laravel commands
echo "Switching to www user for Laravel operations..."

# Wait for database
echo "Checking database connection..."
until gosu www php -r "
try {
    new PDO(
        'mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}',
        '${DB_USERNAME}',
        '${DB_PASSWORD}'
    );
    echo 'Database connection successful\n';
    exit(0);
} catch (Exception \$e) {
    echo 'Database not ready: ' . \$e->getMessage() . \"\n\";
    exit(1);
}" ; do
    echo "Waiting for database..."
    sleep 2
done

echo "Database is ready!"

gosu www php artisan config:clear

# Run migrations if needed
echo "Running database migrations..."
gosu www php artisan migrate --force

gosu www php artisan cache:clear

# Cache configuration for better performance
gosu www php artisan config:cache

# Setting permissions for nginx files
mkdir -p /var/log/nginx
chown -R www-data:www-data /var/log/nginx


# Start both PHP-FPM and Nginx using supervisor
echo "Starting PHP-FPM and Nginx with supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
