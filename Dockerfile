FROM composer:2 AS BUILDER

COPY . ./app
RUN cd ./app && composer install --prefer-dist --no-interaction --no-progress --no-scripts

FROM php:8-apache AS APP_IMAGE

RUN apt-get update && \
    docker-php-ext-install pdo_mysql && \
    a2enmod rewrite && \
    service apache2 restart

COPY --from=builder ./app/* .
RUN cp .env.example .env && php artisan key:gen

EXPOSE 80
