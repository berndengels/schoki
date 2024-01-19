#!/bin/bash

php="/usr/local/Cellar/php@8.2/8.2.13/bin/php"
redis-cli FLUSHALL
composer="/usr/local/bin/composer"

echo "clear all caches"
if [ -f "./bootstrap/cache/packages.php" ]; then
	rm ./bootstrap/cache/packages.php
fi
if [ -f "./bootstrap/cache/services.php" ]; then
	rm ./bootstrap/cache/services.php
fi
$php artisan cache:clear
$php artisan config:clear
$php artisan route:clear
$php artisan view:clear
$php $composer dumpautoload
printf 'all DONE \360\237\230\216\n'
