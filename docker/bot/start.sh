#!/bin/bash

cd /var/www

chown www-data:www-data ./karmabot -R
chmod 0777 ./karmabot

php -v

rm /var/www/karmabot/storage/logs/laravel.log
touch /var/www/karmabot/storage/logs/laravel.log
tail -f /var/www/karmabot/storage/logs/laravel.log


cd /var/www/karmabot

cp -n ../.env.example .env

composer install

php artisan key:generate
php artisan migrate --force --seed
