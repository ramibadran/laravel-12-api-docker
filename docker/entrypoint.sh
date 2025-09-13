#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

echo "Creating env file for env $APP_ENV"
case "$APP_ENV" in
"local")
    echo "Copying env_docker ... "
    cp env_docker .env
    php artisan migrate
    php artisan optimize clear
    php artisan view:clear
    php artisan route:clear
    php artisan config:clear
    ;;
"stg")
    echo "Copying env_docker ... "
    cp .env_stg .env
    php artisan migrate
    php artisan clear
    php artisan optimize:clear
    php artisan migrate

    # Fix files ownership.
    chown -R www-data .
    chown -R www-data /app/storage
    chown -R www-data /app/storage/logs
    chown -R www-data /app/storage/framework
    chown -R www-data /app/storage/framework/sessions
    chown -R www-data /app/bootstrap
    chown -R www-data /app/bootstrap/cache

    # Set correct permission.
    chmod -R 775 /app/storage
    chmod -R 775 /app/storage/logs
    chmod -R 775 /app/storage/framework
    chmod -R 775 /app/storage/framework/sessions
    chmod -R 775 /app/bootstrap
    chmod -R 775 /app/bootstrap/cache
    ;;
"prod")
    echo "Copying .env_prod ... "
    cp .env_prod .env
    php artisan migrate
    php artisan clear
    php artisan optimize:clear
    php artisan migrate

    # Fix files ownership.
    chown -R www-data .
    chown -R www-data /app/storage
    chown -R www-data /app/storage/logs
    chown -R www-data /app/storage/framework
    chown -R www-data /app/storage/framework/sessions
    chown -R www-data /app/bootstrap
    chown -R www-data /app/bootstrap/cache

    # Set correct permission.
    chmod -R 775 /app/storage
    chmod -R 775 /app/storage/logs
    chmod -R 775 /app/storage/framework
    chmod -R 775 /app/storage/framework/sessions
    chmod -R 775 /app/bootstrap
    chmod -R 775 /app/bootstrap/cache
    
;;
esac

php-fpm -D
nginx -g "daemon off;"
