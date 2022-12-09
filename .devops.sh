#!/bin/bash

composer update
composer install --ignore-platform-reqs

php artisan storage:link
php artisan migrate

npm install
npm run prod

php artisan sentry:test