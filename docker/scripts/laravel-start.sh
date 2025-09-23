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

# Wait for database
echo "Checking database connection..."
until php -r "
\$pdo = new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');
echo 'Database connection successful\n';
" 2>/dev/null; do
    echo "Waiting for database..."
    sleep 2
done

echo "Database is ready!"

php artisan config:clear

# Run migrations if needed
echo "Running database migrations..."
php artisan migrate --force

php artisan cache:clear

# Cache configuration for better performance
php artisan config:cache

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm
