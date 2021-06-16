FROM php:7-apache

ENV APP_ENV=$APP_ENV

RUN apt-get update \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && service apache2 restart \
    && apt-get install supervisor -y \
        && apt autoremove -y \

    # setup php_ini
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
        && echo "xdebug.mode=develop,coverage,debug,gcstats,profile,trace" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
        && echo "xdebug.remote_enable=on" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
        && echo "xdebug.remote_autostart=on" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"

COPY . .

RUN chmod 777 ./deploy.sh
WORKDIR /var/www/html
CMD ./deploy.sh
