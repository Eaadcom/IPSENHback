FROM php:7-apache

ENV APP_ENV=$APP_ENV

RUN apt-get update && \
    docker-php-ext-install pdo_mysql pcntl && \
    a2enmod rewrite && \
    service apache2 restart && \
    apt-get update && \
    apt-get install supervisor -y && apt autoremove -y

COPY . .

RUN chmod 777 ./deploy.sh
WORKDIR /var/www/html
CMD ./deploy.sh
