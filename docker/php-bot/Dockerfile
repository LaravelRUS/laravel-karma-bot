#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#

FROM php:7.1

RUN DEBIAN_FRONTEND=noninteractive

# Install dotdeb repo
RUN echo "deb http://packages.dotdeb.org jessie all" > /etc/apt/sources.list.d/dotdeb.list \
    && curl -sS https://www.dotdeb.org/dotdeb.gpg | apt-key add - \
    && apt-get update

# Install "libraries" and "Software's"
RUN apt-get update && \
    apt-get install -y \
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
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Opcache
RUN docker-php-ext-install opcache && \
    docker-php-ext-enable opcache
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Zip
RUN pecl install zip && \
    docker-php-ext-enable zip

# Ev
RUN pecl install ev && \
    docker-php-ext-enable ev

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

ADD ./laravel.ini /usr/local/etc/php/conf.d

RUN rm -r /var/lib/apt/lists/*

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

