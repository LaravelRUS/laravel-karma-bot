#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#

FROM php:7.1

#
#--------------------------------------------------------------------------
# Software's Installation
#--------------------------------------------------------------------------
#
# Installing tools and PHP extentions using "apt", "docker-php", "pecl",
#

# Install "curl", "libmemcached-dev", "libpq-dev", "libjpeg-dev",
#         "libpng12-dev", "libfreetype6-dev", "libssl-dev", "libmcrypt-dev",
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        curl \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng12-dev \
        libfreetype6-dev \
        libssl-dev \
        libmcrypt-dev

# Mcrypt extention
RUN docker-php-ext-install mcrypt

# Mysql (PDO) extention
RUN docker-php-ext-install pdo_mysql

# Pcntl
RUN docker-php-ext-install pcntl

# Sockets
RUN docker-php-ext-install sockets

# Gmp
RUN docker-php-ext-install bcmath

# GD
RUN docker-php-ext-configure gd \
        --enable-gd-native-ttf \
        --with-jpeg-dir=/usr/lib \
        --with-freetype-dir=/usr/include/freetype2 && \
    docker-php-ext-install gd

# Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Opcache
RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Zip
RUN pecl install zip && docker-php-ext-enable zip

# Ev
RUN pecl install ev && docker-php-ext-enable ev

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

ADD ./laravel.ini /usr/local/etc/php/conf.d

RUN rm -r /var/lib/apt/lists/*

RUN usermod -u 1000 www-data

WORKDIR /var/www/karmabot

USER root

# Poehali (c)
ADD ./start.sh /start.sh
RUN chmod 755 /start.sh

USER www-data

CMD ["/start.sh"]
