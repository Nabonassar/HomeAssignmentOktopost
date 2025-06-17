#!/bin/bash
chown -R www-data:www-data /var/www/html/storage && 
composer install --working-dir=/var/www/html && 
php artisan migrate &&
php artisan db:seed --class=BootstrapSeeder
apache2-foreground