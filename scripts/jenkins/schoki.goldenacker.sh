#!/usr/bin/env bash

PATH="/opt/plesk/node/9/bin:/opt/plesk/php/7.2/bin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin"
composer=./composer
dir=$(pwd)
pagePath="$dir/schoki.goldenacker.de"
user=$(whoami)
npm="/home/schoki/local/bin/npm"
echo "path: $PATH"
echo "user: $user"
cd $pagePath
echo "dir: $(pwd)"

git fetch origin
git pull

if ${COPY_SCRIPTS}; then
    cp -f ./scripts/public-htaccess.tpl ./public_html/.htaccess
    cp -f ./scripts/phpunit.dusk.xml.goldenacker phpunit.dusk.xml
    cp -f .env.goldenacker .env
fi

if ${CHMOD}; then
    chmod -R ugo+rwx storage/logs public_html/media public_html/uploads storage/framework storage/app bootstrap/cache tests/Browser/screenshots
fi

if ${COMPOSER_INSTALL}; then
    echo "composer install ;-)"
    php $composer install
fi
if ${COMPOSER_UPDATE}; then
    echo "composer update ;-)"
    php $composer update
fi
if ${NPM_INSTALL}; then
    echo "npm install ;-)"
    npm cache verify
    npm install
fi
if ${CLEAR}; then
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
fi
npm run dev

if ${RUN_TEST}; then
   echo "run tests ;-)"
   php artisan dusk --debug --verbose
   php artisan my-test-report:generate
   npm run dev
fi
