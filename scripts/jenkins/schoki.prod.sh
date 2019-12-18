PATH="/home/schoki/local/bin:/bin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin"
dir=$(pwd)
pagePath="$dir/web/schokoladen-mitte.de/www.schokoladen-mitte.de"
user=$(whoami)
composer=./composer
npm="/home/schoki/local/bin/npm"
echo "path: $PATH"
echo "user: $user"
cd $pagePath
echo "dir: $(pwd)"

#echo "git fetch origin/schoki ;-)"
#git fetch origin
#git fetch origin/schoki
echo "git pull ;-)"
git pull

if ${COPY_SCRIPTS}; then
    cp -f ./scripts/public-htaccess.tpl ./public_html/.htaccess
    cp -f ./scripts/phpunit.dusk.xml.schoki phpunit.dusk.xml
    cp -f .env.prod .env
fi

case ${COMPOSER} in
	install)
                echo "composer install ;-)"
                php $composer install
		break
		;;
	update)
                echo "composer update ;-)"
                php $composer update
		break
		;;
	*)
		echo "nothing to do for composer!"
		;;
esac

case ${NPM} in
	install)
                echo "npm install ;-)"
                $npm cache verify
                $npm install
		break
		;;
	update)
                echo "npm update ;-)"
                $npm cache verify
                $npm install
		break
		;;
	*)
		echo "nothing to do for npm!"
		;;
esac

if ${GIT_RESET}; then
    git reset --hard
fi
if ${CLEAR}; then
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    $npm run prod
fi
if ${RUN_TEST}; then
   echo "run tests ;-)"
   php artisan dusk --debug --verbose
fi
