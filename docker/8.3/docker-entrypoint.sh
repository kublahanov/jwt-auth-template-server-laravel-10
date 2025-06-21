#!/bin/bash

# Создаем директории, если их нет (для работы без volume)
mkdir -p /var/www/html/storage/{logs,framework/cache,framework/views}
chown -R sail:sail /var/www/html/storage

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
