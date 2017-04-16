#!/bin/bash
php artisan down --retry=2
git pull -r

composer install
npm install

./production.sh

php artisan up
