#!/bin/bash

# Копирование .env
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Генерация ключа приложения
docker compose exec laravel_10-jwt_app php artisan key:generate

# Запуск миграций
docker compose exec laravel_10-jwt_app php artisan migrate --seed

# Установка прав
#docker compose exec laravel chmod -R 775 storage bootstrap/cache
