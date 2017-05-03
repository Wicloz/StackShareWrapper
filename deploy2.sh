#!/bin/bash

composer install
yarn install

./production.sh

php artisan up
