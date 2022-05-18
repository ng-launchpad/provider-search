#!/bin/bash

# Install composer dependencies
composer install

# Migrate database and link storage folders
vendor/bin/sail artisan migrate
vendor/bin/sail artisan storage:link

# Refresh IDE helper files
make idehelper

# Install and build frontend
vendor/bin/sail npm install
vendor/bin/sail npm run dev
