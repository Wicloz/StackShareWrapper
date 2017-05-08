#!/bin/bash

php artisan config:cache
php artisan route:cache
php artisan migrate --seed --force
npm run production
