FROM php:8-apache

RUN apt-get update && \
    pecl install redis && \
    docker-php-ext-install pdo_mysql pcntl posix&& \
    docker-php-ext-enable redis && \
    a2enmod rewrite && \
    service apache2 restart && \
    apt-get install supervisor -y && \
    apt autoremove -y && \
    # supervisord configuration
    echo "\n\
[program:lumen] \n\
process_name=%(program_name)s_%(process_num)02d \n\
command=php artisan queue:work --sleep=3 --tries=3 \n\
directory=/var/www/html \n\
autostart=true \n\
autorestart=true \n\
numprocs=1 \n\
redirect_stderr=true \n\
stdout_logfile=/var/www/html/storage/logs/laravel-worker.log \n\
startsecs=0" >> /etc/supervisor/supervisord.conf

COPY . .
