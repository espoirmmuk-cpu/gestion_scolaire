#!/bin/sh

set -e

echo "Démarrage de Laravel..."

php artisan config:clear
php artisan cache:clear
php artisan view:clear

php artisan storage:link || true

echo "Démarrage de PHP-FPM..."
php-fpm -D

echo "Démarrage de Nginx..."
nginx -g "daemon off;"