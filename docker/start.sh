#!/bin/sh

set -e

echo "Démarrage de Laravel..."

# Créer le certificat SSL Aiven à partir de la variable Render
if [ -n "$AIVEN_CA_CERT" ]; then
    echo "$AIVEN_CA_CERT" > /etc/ssl/certs/aiven-ca.pem
    chmod 644 /etc/ssl/certs/aiven-ca.pem
    echo "Certificat SSL Aiven installé."
else
    echo "ATTENTION : AIVEN_CA_CERT n'est pas défini."
fi

php artisan config:clear
php artisan cache:clear
php artisan view:clear

php artisan storage:link || true

echo "Démarrage de PHP-FPM..."
php-fpm -D

echo "Démarrage de Nginx..."
nginx -g "daemon off;"