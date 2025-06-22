FROM ubuntu:22.04

LABEL maintainer="Roman Kondrashov"

ARG WWWUSER=1000
ARG WWWGROUP=1000
#ARG NODE_VERSION=20
#ARG POSTGRES_VERSION=15

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC
ENV SUPERVISOR_PHP_COMMAND="/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan serve --host=0.0.0.0 --port=80"

# Фиксация версий PHP
RUN echo "APT::Get::Never-Include-Phased-Updates \"true\";" > /etc/apt/apt.conf.d/99no-phased-updates && \
    echo "Package: php*" > /etc/apt/preferences.d/php-pinning && \
    echo "Pin: release o=Ondřej Surý" >> /etc/apt/preferences.d/php-pinning && \
    echo "Pin-Priority: 1001" >> /etc/apt/preferences.d/php-pinning

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Установка базовых пакетов с libcap2-bin для setcap
RUN apt-get update \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 dnsutils librsvg2-bin fswatch \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update

# Явное удаление всех версий PHP перед установкой
RUN apt-get remove -y php* && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get update

# Установка только PHP 8.3 и его модулей
RUN apt-get install -y --no-install-recommends \
        php8.3 \
        php8.3-cli \
        php8.3-common \
        php8.3-opcache \
        php8.3-dev \
        php8.3-pgsql \
        php8.3-sqlite3 \
        php8.3-gd \
        php8.3-curl \
        php8.3-imap \
        php8.3-mysql \
        php8.3-mbstring \
        php8.3-xml \
        php8.3-zip \
        php8.3-bcmath \
        php8.3-soap \
        php8.3-intl \
        php8.3-readline \
        php8.3-ldap \
        php-yaml \
        php8.3-msgpack \
        php8.3-igbinary \
        php8.3-redis \
        php8.3-swoole \
        php8.3-memcached \
        php8.3-pcov \
        php8.3-imagick \
        php8.3-xdebug \
    && update-alternatives --set php /usr/bin/php8.3 \
    && update-alternatives --set phar /usr/bin/phar8.3 \
    && update-alternatives --set phar.phar /usr/bin/phar.phar8.3 \
    && apt-mark hold php8.3* \
    && echo "php8.3 hold" | dpkg --set-selections

# Явное удаление возможных остатков PHP 8.4
RUN apt-get purge -y php8.4* && \
    apt-get autoremove -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && ln -s /usr/local/bin/composer /usr/bin/composer

# Установка прав для работы на порту 80
RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.3

# Установка Node.js и других зависимостей
#RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
#    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
#    && apt-get update \
#    && apt-get install -y nodejs \
#    && npm install -g npm \
#    && npm install -g pnpm \
#    && npm install -g bun \
#    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /etc/apt/keyrings/yarn.gpg >/dev/null \
#    && echo "deb [signed-by=/etc/apt/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
#    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /etc/apt/keyrings/pgdg.gpg >/dev/null \
#    && echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
#    && apt-get update \
#    && apt-get install -y yarn \
#    && apt-get install -y mysql-client \
#    && apt-get install -y postgresql-client-$POSTGRES_VERSION \
#    && apt-get -y autoremove \
#    && apt-get clean \
#    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN apt-get -y autoremove \
    && apt-get -y autoclean

# Создание пользователя и групп
RUN groupadd --force -g $WWWGROUP app
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 app

# Создание необходимых директорий
RUN mkdir -p /var/www/html/storage/{logs,framework/cache,framework/views} \
    && chown -R app:app /var/www/html/storage

# Копирование конфигурационных файлов
COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/8.3/cli/conf.d/99-app.ini
RUN chmod +x /usr/local/bin/start-container
COPY .gitconfig /home/app/.gitconfig
RUN chown app:app /home/app/.gitconfig

# Универсальный entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]

USER app

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
