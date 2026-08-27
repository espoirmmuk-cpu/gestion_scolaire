#!/bin/sh

set -e

echo "Demarrage de Laravel..."

# Certificat SSL Aiven
if [ -n "$AIVEN_CA_CERT" ]; then
    printf '%s\n' "$AIVEN_CA_CERT" > /etc/ssl/certs/aiven-ca.pem
    chmod 644 /etc/ssl/certs/aiven-ca.pem
    echo "Certificat SSL Aiven installe."
else
    echo "ATTENTION : AIVEN_CA_CERT n'est pas defini."
fi

# Nettoyage des caches
php artisan config:clear
php artisan view:clear

echo "Demarrage de PHP-FPM..."
php-fpm -D

echo "Verification de PHP-FPM..."
sleep 2

echo "Demarrage de Nginx..."
nginx -g "daemon off;"