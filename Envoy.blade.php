@servers(['test' => 'goldenacker@schoki.goldenacker.de', 'prod' => 'schoki@schokoladen-mitte.de'])

@task('test', ['on' => 'test'])
echo "testing...";
cd ./schoki.goldenacker.de
pwd
git pull origin frontend
./clearme
npm run prod
@endtask
