FROM php:7-apache

ENV APP_ENV=$APP_ENV

RUN apt-get update && \
    docker-php-ext-install pdo_mysql pcntl && \
    a2enmod rewrite && \
    service apache2 restart && \
    apt-get update && \
    apt-get install supervisor -y && apt autoremove -y \
    yes | pecl install xdebug \
        && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
        && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
        && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && docker-php-ext-enable xdebug

COPY . .

RUN chmod 777 ./deploy.sh
WORKDIR /var/www/html
CMD ./deploy.sh
