#!/bin/bash

cd /var/www

chown www-data:www-data ./karmabot -R
chmod 0777 ./karmabot

php -v

cd /var/www

cp -n .env.example karmabot/.env

composer install

php ./karmabot/artisan key:generate
php ./karmabot/artisan migrate --force
php ./karmabot/artisan watch > /dev/null
