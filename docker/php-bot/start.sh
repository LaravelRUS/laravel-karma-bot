#!/bin/bash

cd /var/www

php -r "copy('https://getcomposer.org/composer.phar', 'composer.phar');"
php composer.phar install
rm composer.phar
rm -rf .composer

# KarmaBot
cd /var/www/karmabot
cp -n ../.env.example .env

php artisan key:generate
php artisan migrate --force --seed
