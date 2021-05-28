FROM php:8-apache

ENV APP_ENV=$APP_ENV

RUN apt-get update && \
    pecl install redis && \
    docker-php-ext-install pdo_mysql pcntl posix&& \
    docker-php-ext-enable redis && \
    a2enmod rewrite && \
    service apache2 restart && \
    apt-get install supervisor -y && \
    apt autoremove -y && \
    # setup deploy.sh
    touch deploy.sh && \
    chmod +x deploy.sh

COPY supervisord.conf /etc/supervisor/supervisord.conf
COPY . .

CMD ./deploy.sh
