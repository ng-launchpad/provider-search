#!/bin/bash

composer update
composer install --ignore-platform-reqs

php artisan storage:link
php artisan migrate

php artisan sentry:test