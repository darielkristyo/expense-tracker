#!/bin/sh
set -e

cd /var/www/html

# Ensure Laravel can write its runtime files.
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Run migrations whenever the application container starts.
php artisan package:discover --ansi
php artisan migrate --force

exec "$@"
