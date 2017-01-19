#!/bin/bash

cd /var/www

chown www-data:www-data ./karmabot -R
chmod 0777 ./karmabot

cd /var/www

cp -n .env.example karmabot/.env

composer install

usermod -u 1000 www-data

php ./karmabot/artisan key:generate
php ./karmabot/artisan migrate --force

php ./karmabot/artisan queue:work --queue=high,default
