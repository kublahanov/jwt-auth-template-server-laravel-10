#!/bin/bash

# Установка прав на папки
sudo chown -R $USER:$USER .
sudo chmod -R 775 storage bootstrap/cache

# Копирование .env (если не существует)
if [ ! -f ".env" ]; then
    cp .env.example .env
    # Настройка прав для .env
    chmod 664 .env
fi

# Запуск команд внутри контейнера с правильным пользователем
docker compose exec -T laravel_10-jwt_app bash -c "
    # Установка прав внутри контейнера
    chown -R app:app /var/www/html
    chmod -R 775 storage bootstrap/cache

    # Генерация ключа
    php artisan key:generate

    # Ждем готовности MySQL
    while ! php artisan db:monitor >/dev/null 2>&1; do
        echo 'Waiting for database connection...'
        sleep 1
    done

    # Миграции и сиды
    php artisan migrate --force --seed
"
