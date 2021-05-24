FROM php:8-apache

RUN apt-get update && \
    pecl install redis && \
    docker-php-ext-install pdo_mysql pcntl posix&& \
    docker-php-ext-enable redis && \
    a2enmod rewrite && \
    service apache2 restart

COPY . .

EXPOSE 80
