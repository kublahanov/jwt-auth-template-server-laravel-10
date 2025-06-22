#!/bin/bash

# Making .env from .env.example, if not yet
#if [ ! -f .env ]; then
#    cp .env.example .env
#fi

# Starting Docker stack
./vendor/bin/sail up -d

# Setup composer dependencies
./vendor/bin/sail composer install

# Generating application key
#./vendor/bin/sail artisan key:generate --ansi

# Starting migrations and seeds
./vendor/bin/sail artisan migrate --seed
