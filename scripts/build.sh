#!/bin/bash

# Migrate database and link storage folders
vendor/bin/sail artisan migrate
vendor/bin/sail artisan storage:link

# Install and build frontend
vendor/bin/sail npm install
vendor/bin/sail npm run dev

# Refresh IDE helper files
make idehelper
