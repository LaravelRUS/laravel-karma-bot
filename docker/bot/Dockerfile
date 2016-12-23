#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#

FROM php:7.1-zts

RUN DEBIAN_FRONTEND=noninteractive

# Install dotdeb repo
RUN echo "deb http://packages.dotdeb.org jessie all" > /etc/apt/sources.list.d/dotdeb.list \
    && curl -sS https://www.dotdeb.org/dotdeb.gpg | apt-key add - \
    && apt-get update

# Install required libs
RUN apt-get update && \
    apt-get install -y \
        git \
        libcurl4-openssl-dev \
        libedit-dev \
        libssl-dev \
        libxml2-dev \
        libsqlite3-dev \
        sqlite3 \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng12-dev \
        libfreetype6-dev \
        libssl-dev \
        libmcrypt-dev \
    && apt-get clean

#
# Configure
#
RUN docker-php-ext-configure gd \
        --enable-gd-native-ttf \
        --with-jpeg-dir=/usr/lib \
        --with-freetype-dir=/usr/include/freetype2

#
# Install extensions
#
RUN docker-php-ext-install \
        mcrypt \
        pdo_mysql \
        pcntl \
        sockets \
        bcmath \
        opcache \
        gd

#
# Install non standard extensions
#
RUN pecl install xdebug
RUN pecl install zip
RUN pecl install ev

#
# Export configs
#
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

#
# Enable non standard extensions
#
RUN docker-php-ext-enable \
    xdebug \
    opcache \
    zip \
    ev

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

ADD ./laravel.ini /usr/local/etc/php/conf.d

RUN rm -r /var/lib/apt/lists/*

#####################################
# Crontab
#####################################
COPY ./crontab /var/spool/cron/crontabs

#####################################
# Composer:
#####################################

# Install composer and add its bin to the PATH.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN echo "" >> ~/.bashrc && \
    echo 'export PATH="/var/www/vendor/bin:$PATH"' >> ~/.bashrc

ADD ./start.sh /start.sh
RUN chmod 0755 /start.sh

WORKDIR /var/www/karmabot

RUN usermod -u 1000 www-data
USER www-data

