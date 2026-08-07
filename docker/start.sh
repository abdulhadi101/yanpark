#!/bin/sh
set -e

echo "Starting Laravel app..."

# Ensure the SQLite database file exists (it lives in a persisted volume)
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Ensure writable directories (including the volume-mounted database dir)
mkdir -p /var/www/html/storage/app/public \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage \
       /var/www/html/bootstrap/cache \
       /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Fix Nginx temp permissions (critical for file uploads)
mkdir -p /var/lib/nginx/tmp/client_body
chown -R www-data:www-data /var/lib/nginx
chmod -R 775 /var/lib/nginx

echo "Linking storage..."
php artisan storage:link --force 2>/dev/null || true

echo "Running database migrations..."
php artisan migrate --force || true

echo "Clearing stale caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Running Laravel optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting services via Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf