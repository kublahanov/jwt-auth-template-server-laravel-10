#!/bin/bash

# Установка прав
chown -R app:app /var/www/html
chmod -R 775 /var/www/html/storage

# Создание необходимых директорий
mkdir -p /var/log/supervisor /var/run/supervisor
chown -R app:app /var/log/supervisor /var/run/supervisor

# Ожидание MySQL (альтернатива без nc)
#while ! php -r "new PDO('mysql:host=mysql;dbname=${DB_DATABASE:-laravel}', '${DB_USERNAME:-sail}', '${DB_PASSWORD:-password}');" >/dev/null 2>&1; do
#    echo 'Waiting for MySQL...'
#    sleep 1
#done

# Ожидание Redis
#while ! php -r "(new Redis())->connect('redis', 6379);" >/dev/null 2>&1; do
#    echo 'Waiting for Redis...'
#    sleep 1
#done

# Если запускаем через docker run без параметров
if [ "$1" = "bash" ]; then
    exec "$@"
elif [ "$1" = "artisan" ]; then
    exec /usr/bin/php /var/www/html/artisan "${@:2}"
elif [ "$#" -eq 0 ]; then
    # Режим по умолчанию (как в docker compose)
    exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
else
    exec "$@"
fi
