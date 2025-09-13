#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

#if [ ! -f ".env" ]; then
    #echo "Creating env file for env $APP_ENV"
    #cp .env_docker .env
#else
    #echo "env file exists."
#fi

echo "Creating env file for env $APP_ENV"
case "$APP_ENV" in
"local")
    echo "Copying .env.example ... "
    cp env_docker .env
;;
"prod")
    echo "Copying .env.prod ... "
    cp .env_prod .env
;;
esac

php artisan migrate
php artisan optimize clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

php-fpm -D
nginx -g "daemon off;"
