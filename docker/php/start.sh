#!/bin/bash

touch /var/log/php-fpm.log

cd /var/www

chown www-data:www-data ./karmabot -R
chmod 0777 ./karmabot

cd /var/www/karmabot

cp -n .env.example .env

usermod -u 1000 www-data

composer install

php ./artisan key:generate
php ./artisan migrate --force

/usr/bin/nohup php ./artisan queue:work --queue=high,default > ./docker/logs/queue.log 2>&1 &

php-fpm
