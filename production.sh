#!/bin/bash
php artisan config:cache
php artisan route:cache
php artisan migrate --force
npm run production
