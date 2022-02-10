#!/bin/bash

# Generate IDE Helper files
vendor/bin/sail artisan ide-helper:generate
vendor/bin/sail artisan ide-helper:models --write
vendor/bin/sail artisan ide-helper:meta
