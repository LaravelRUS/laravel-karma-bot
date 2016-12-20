#!/bin/bash

cd /var/www

php -v

php composer.phar install
cp -n .env.example ./karmabot/.env

cd /var/www/karmabot

php artisan key:generate
php artisan migrate --force --seed
