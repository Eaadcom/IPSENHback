#!/bin/bash

echo "Deploying app in $APP_ENV environment"

# start supervisor
cp ./lumen_worker.conf /etc/supervisor/conf.d/lumen_worker.conf
supervisord -c /etc/supervisor/supervisord.conf

# check supervisor status
service supervisor status

# run artisan migration command & start web server
if [ "$APP_ENV" == "production" ]
then
   php artisan migrate && apache2-foreground
else
    php artisan migrate:fresh --seed && apache2-foreground
fi;
