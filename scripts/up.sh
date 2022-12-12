#!/bin/bash

# Copy .env if not exists
if [ ! -f ./.env ]; then
    echo "No .env file detected. Copying .env.example"
    cp ./.env.example ./.env
fi

# If there is no vendor folder then execute a build
if [ ! -d ./vendor ]; then
    echo "Building project for the first time"

    # install composer dependencies
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/opt \
        -w /opt \
        laravelsail/php80-composer:latest \
        composer install --ignore-platform-reqs

    # bring containers up
    vendor/bin/sail up -d

    # generate application key
    vendor/bin/sail artisan key:generate

    # take a nap
    echo "Waiting for the DB to come up. Sleeping 10 seconds..."
    sleep 10s

    # migrate fresh and build
    make fresh
    make build

else
    vendor/bin/sail up -d
fi
