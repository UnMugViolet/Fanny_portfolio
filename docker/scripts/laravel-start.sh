#!/bin/bash
set -e

echo "Starting Laravel application..."

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
