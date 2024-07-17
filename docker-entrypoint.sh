#!/bin/bash
set -e

# Migrate the database
php artisan migrate --force

# Start the Laravel development server
exec php artisan serve --host 0.0.0.0 --port 8000