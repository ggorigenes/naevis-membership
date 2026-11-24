#!/usr/bin/env bash
set -e

echo "Running database migrations..."
php artisan migrate --force

echo "Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting queue worker in background..."
php artisan queue:work --daemon --tries=3 &