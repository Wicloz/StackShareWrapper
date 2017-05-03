#!/bin/bash

php artisan down --retry=2
git pull -r
