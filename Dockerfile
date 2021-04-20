FROM php:8-apache

RUN apt-get update && \
    docker-php-ext-install pdo_mysql && \
    a2enmod rewrite && \
    service apache2 restart

COPY . /var/www/html

EXPOSE 80
