#!/bin/bash

# Проверяем версию PHP
#CURRENT_PHP=$(php -v | head -n 1 | cut -d' ' -f2 | cut -d'.' -f1-2)
#if [ "$CURRENT_PHP" != "8.3" ]; then
#    echo "ERROR: Wrong PHP version detected ($CURRENT_PHP). Forcing PHP 8.3..."
#    update-alternatives --set php /usr/bin/php8.3
#    exec php "$0" "$@"
#fi

# Создаем директории, если их нет (для работы без volume)
#mkdir -p /var/www/html/storage/{logs,framework/cache,framework/views}
#chown -R sail:sail /var/www/html/storage

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
