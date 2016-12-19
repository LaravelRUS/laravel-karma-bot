#!/bin/bash

php -r "copy('https://getcomposer.org/composer.phar', 'composer.phar');"
php composer.phar install
rm composer.phar

# KarmaBot
cd /var/www/karmabot
cp -n ../.env.example .env

php artisan key:generate
php artisan migrate --force --seed
