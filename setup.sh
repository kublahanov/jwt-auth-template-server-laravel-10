#!/bin/bash

# Setup composer dependencies
composer install

# Making .env from .env.example, if not yet
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Starting Docker stack
./vendor/bin/sail up -d

# Generating application key
./vendor/bin/sail artisan key:generate --ansi

# Starting migrations and seeds
./vendor/bin/sail artisan migrate --seed
