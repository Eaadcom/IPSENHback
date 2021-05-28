#!/bin/bash

echo "Deploying app in $APP_ENV environment"

# run artisan migration command
if [ "$APP_ENV" == "production" ]
then
   php artisan migrate --fresh
else
    php artisan migrate:fresh --seed
fi;

# start supervisor
cp ./lumen_worker.conf /etc/supervisor/conf.d/lumen_worker.conf
supervisord -c /etc/supervisor/supervisord.conf

# check supervisor status
service supervisor status

#start webserver
apache2-foreground
