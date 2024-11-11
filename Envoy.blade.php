@servers(['prod' => 'schoki@schokoladen-mitte.de','test' => 'goldenacker@goldenacker.de'])

@task('test', ['on' => 'test'])
echo "testing...";
cd ./schoki.goldenacker.de
pwd
git config pull.rebase false
git pull origin frontend
npm run prod
@endtask

@task('prod', ['on' => 'prod'])
echo "prod...";
cd ./web/schokoladen-mitte.de/www.schokoladen-mitte.de
pwd
git config pull.rebase false
git pull origin master
@endtask
