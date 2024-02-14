#!/usr/bin/env bash

echo "running install.sh"
cd /var/www/html || exit
composer install --ignore-platform-reqs
chmod -R 777 /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage
[ ! -f /var/www/html/.env ] && cp /var/www/html/.env.dist /var/www/html/.env
apache2-foreground
